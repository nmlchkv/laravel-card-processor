<?php

namespace App\Infrastructure\Services;

use App\Domain\Services\BinLookupServiceInterface;
use App\Domain\Constants\AppConstants;
use Illuminate\Support\Facades\Http;

class BinLookupService implements BinLookupServiceInterface
{
    public function lookup(string $cardNumber): array
    {
        $bin = substr($cardNumber, 0, AppConstants::BIN_LENGTH);
        
        try {
            $response = Http::timeout(AppConstants::HTTP_TIMEOUT)
                ->get(AppConstants::BINLIST_API_URL . $bin);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'scheme' => $data['scheme'] ?? AppConstants::UNKNOWN_SCHEME,
                    'brand' => $data['brand'] ?? AppConstants::UNKNOWN_BRAND,
                    'bank' => $data['bank']['name'] ?? AppConstants::UNKNOWN_BANK,
                    'bin' => $bin
                ];
            }
        } catch (\Exception $e) {
        }

        return $this->getStubData($cardNumber, $bin);
    }

    private function getStubData(string $cardNumber, string $bin): array
    {
        if (str_starts_with($cardNumber, AppConstants::VISA_PREFIX)) {
            return [
                'scheme' => AppConstants::VISA_SCHEME,
                'brand' => AppConstants::VISA_BRAND,
                'bank' => AppConstants::SBERBANK,
                'bin' => $bin
            ];
        }

        if (str_starts_with($cardNumber, AppConstants::MASTERCARD_PREFIX)) {
            return [
                'scheme' => AppConstants::MASTERCARD_SCHEME,
                'brand' => AppConstants::MASTERCARD_BRAND,
                'bank' => AppConstants::VTB,
                'bin' => $bin
            ];
        }

        return [
            'scheme' => AppConstants::UNKNOWN_SCHEME,
            'brand' => AppConstants::UNKNOWN_BRAND,
            'bank' => AppConstants::UNKNOWN_BANK,
            'bin' => $bin
        ];
    }
}
