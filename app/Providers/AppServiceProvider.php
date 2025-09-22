<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Services\BinLookupServiceInterface;
use App\Infrastructure\Services\BinLookupService;
use App\Infrastructure\Repositories\UploadRepositoryInterface;
use App\Infrastructure\Repositories\UploadRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BinLookupServiceInterface::class, BinLookupService::class);
        $this->app->bind(UploadRepositoryInterface::class, UploadRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
