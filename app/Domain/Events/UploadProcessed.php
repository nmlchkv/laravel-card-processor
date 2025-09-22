<?php

namespace App\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadProcessed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly int $uploadId,
        public readonly string $resultPath,
        public readonly string $callbackUrl,
        public readonly string $callbackToken
    ) {}
}

