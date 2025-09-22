<?php

namespace App\Infrastructure\Repositories;

use App\Models\Upload;

class UploadRepository implements UploadRepositoryInterface
{
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
}

