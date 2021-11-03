<?php

namespace App\Providers;

use App\Services\Uploader\ImageUploader;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

final class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(ImageUploader::class)
            ->needs(Filesystem::class)
            ->give(function ($app) {
                return $app->get('filesystem')->disk('public');
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
