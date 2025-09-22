<?php

namespace App\Application\Commands;

use Illuminate\Http\UploadedFile;

class ProcessUploadCommand
{
    public function __construct(
        public readonly UploadedFile $file,
        public readonly string $callbackUrl,
        public readonly string $callbackToken
    ) {}
}

