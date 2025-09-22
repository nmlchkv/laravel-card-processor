<?php

namespace App\Application\Services;

use App\Application\Commands\ProcessUploadCommand;
use App\Domain\Constants\AppConstants;
use App\Infrastructure\Repositories\UploadRepositoryInterface;

class UploadApplicationService
{
    public function __construct(
        private UploadRepositoryInterface $uploadRepository
    ) {}

    public function processUpload(ProcessUploadCommand $command): array
    {
        $upload = $this->uploadRepository->create([
            'original_filename' => $command->file->getClientOriginalName(),
            'status' => AppConstants::STATUS_QUEUED,
            'callback_url' => $command->callbackUrl,
            'callback_token' => $command->callbackToken,
        ]);

        $path = $command->file->storeAs("uploads/{$upload->id}", AppConstants::INPUT_FILENAME);

        return [
            'id' => $upload->id,
            'status' => $upload->status,
        ];
    }

}
