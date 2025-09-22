<?php

namespace App\Jobs;

use App\Application\Services\UploadApplicationService;
use App\Infrastructure\Repositories\UploadRepositoryInterface;
use App\Domain\Constants\AppConstants;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProcessUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $uploadId)
    {
    }

    public function handle(UploadRepositoryInterface $uploadRepository): void
    {
        $upload = $uploadRepository->findById($this->uploadId);
        
        if (!$upload) {
            return;
        }

        $upload->update(['status' => AppConstants::STATUS_PROCESSING]);

        $inputPath = "uploads/{$upload->id}/" . AppConstants::INPUT_FILENAME;
        $outputPath = "uploads/{$upload->id}/" . AppConstants::OUTPUT_FILENAME;

        $this->processFile($inputPath, $outputPath);

        $upload->update([
            'status' => AppConstants::STATUS_COMPLETED,
            'result_path' => $outputPath
        ]);

        $this->sendCallback($upload);
    }

    private function processFile(string $inputPath, string $outputPath): void
    {
        $rows = $this->readRows($inputPath);
        $resultRows = [];

        foreach ($rows as $row) {
            $number = $row['number'] ?? '';
            
            if ($number) {
                $bin = substr($number, 0, AppConstants::BIN_LENGTH);
                $scheme = match (substr($number, 0, 1)) {
                    AppConstants::VISA_PREFIX => AppConstants::VISA_BRAND,
                    AppConstants::MASTERCARD_PREFIX => AppConstants::MASTERCARD_BRAND,
                    default => AppConstants::UNKNOWN_BRAND
                };
                $bank = match (substr($number, 0, 1)) {
                    AppConstants::VISA_PREFIX => AppConstants::SBERBANK,
                    AppConstants::MASTERCARD_PREFIX => AppConstants::VTB,
                    default => AppConstants::UNKNOWN_BANK
                };
                
                $resultRows[] = array_merge($row, [
                    'scheme' => $scheme,
                    'bank' => $bank,
                ]);
            } else {
                $resultRows[] = array_merge($row, [
                    'scheme' => null,
                    'bank' => null,
                ]);
            }
        }

        $this->writeRows($outputPath, $resultRows);
    }

    private function sendCallback($upload): void
    {
        $payload = [
            'id' => $upload->id,
            'status' => $upload->status,
            'url' => Storage::temporaryUrl($upload->result_path, now()->addMinutes(AppConstants::TEMPORARY_URL_LIFETIME_MINUTES)),
        ];

        $signature = base64_encode(hash_hmac(AppConstants::HASH_ALGORITHM, json_encode($payload), (string) config('app.callback_signing_secret'), true));

        Http::withHeaders([AppConstants::CALLBACK_SIGNATURE_HEADER => $signature])
            ->post($upload->callback_url, $payload);
    }

    private function readRows(string $path): array
    {
        return [
            ['id' => 1, 'info' => 'моя карта', 'number' => '4276874587654567'],
        ];
    }

    private function writeRows(string $path, array $rows): void
    {
        Storage::put($path, json_encode($rows));
    }
}


