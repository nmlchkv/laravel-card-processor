<?php

namespace App\Infrastructure\Repositories;

use App\Models\Upload;

interface UploadRepositoryInterface
{
    public function create(array $data): Upload;
    public function findById(int $id): ?Upload;
    public function update(int $id, array $data): bool;
}

