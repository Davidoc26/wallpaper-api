<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ValidatorInterface::class, function () {
            return Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
