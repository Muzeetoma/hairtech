<?php

namespace App\Providers;

use App\Repositories\HairItemRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\HairService;
use App\Services\FileUploadService;
use App\Repositories\HairRepository;
use App\Services\CartService;
use App\Services\HairItemService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(FileUploadService::class, function ($app) {
            return new FileUploadService();
        });
        $this->app->bind(HairService::class, function ($app) {
            $fileUploadService = $app->make(FileUploadService::class);
            $hairRepository = $app->make(HairRepository::class);
            return new HairService($fileUploadService, $hairRepository);
        });
        $this->app->bind(HairItemService::class, function ($app) {
            $fileUploadService = $app->make(FileUploadService::class);
            $hairItemRepository = $app->make(HairItemRepository::class);
            return new HairItemService($fileUploadService, $hairItemRepository);
        });
        $this->app->bind(CartService::class, function ($app) {
            $hairService = $app->make(HairService::class);
            $hairItemService = $app->make(HairItemService::class);
            return new CartService($hairService,$hairItemService);
        });
    }
}
