<?php

namespace App\Http\Controllers;

use App\Application\Commands\ProcessUploadCommand;
use App\Application\Services\UploadApplicationService;
use App\Http\Requests\StoreUploadRequest;
use App\Jobs\ProcessUploadJob;
use Illuminate\Http\JsonResponse;

class UploadController extends Controller
{
    public function __construct(
        private UploadApplicationService $uploadService
    ) {}

    public function store(StoreUploadRequest $request): JsonResponse
    {
        $command = new ProcessUploadCommand(
            $request->file('file'),
            $request->get('callback_url'),
            $request->get('callback_token')
        );

        $result = $this->uploadService->processUpload($command);

        ProcessUploadJob::dispatch($result['id']);

        return response()->json($result, 202);
    }
}


