<?php

namespace Tests\Feature;

use App\Application\Services\UploadApplicationService;
use App\Domain\Constants\AppConstants;
use App\Domain\Services\BinLookupServiceInterface;
use App\Infrastructure\Repositories\UploadRepositoryInterface;
use App\Jobs\ProcessUploadJob;
use App\Models\Upload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_enqueues_job(): void
    {
        Storage::fake('local');
        Bus::fake();

        $this->app->bind(BinLookupServiceInterface::class, function () {
            return new class implements BinLookupServiceInterface {
                public function lookup(string $cardNumber): array
                {
                    return ['scheme' => 'visa', 'brand' => 'visa electron', 'bank' => 'sberbank'];
                }
            };
        });

        $file = \Illuminate\Http\UploadedFile::fake()->create('cards.xlsx', 1, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response = $this->withHeaders([AppConstants::UPLOAD_TOKEN_HEADER => config('app.upload_sender_token')])
            ->postJson('/api/uploads', [
                'file' => $file,
                'callback_url' => 'https://example.com/callback',
                'callback_token' => 'secret-callback-token-1234',
            ]);

        $response->assertStatus(202);

        Bus::assertDispatched(ProcessUploadJob::class);

        $this->assertDatabaseHas('uploads', ['status' => AppConstants::STATUS_QUEUED]);
    }

    public function test_upload_application_service_creates_upload(): void
    {
        Bus::fake();
        
        $this->app->bind(UploadRepositoryInterface::class, function () {
            return new class implements UploadRepositoryInterface {
                public function create(array $data): Upload
                {
                    return Upload::create($data);
                }
                public function findById(int $id): ?Upload
                {
                    return Upload::find($id);
                }
                public function update(int $id, array $data): bool
                {
                    return Upload::where('id', $id)->update($data) > 0;
                }
            };
        });

        $service = $this->app->make(UploadApplicationService::class);
        
        $command = new \App\Application\Commands\ProcessUploadCommand(
            \Illuminate\Http\UploadedFile::fake()->create('test.xlsx'),
            'https://example.com/callback',
            'token123'
        );

        $result = $service->processUpload($command);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals(AppConstants::STATUS_QUEUED, $result['status']);
    }
}


