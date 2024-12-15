<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('string_max', function ($attribute, $value, $parameters, $validator) {
            return (strlen($value) <= 191);
        });

        Validator::replacer('string_max', function ($message, $attribute, $rule, $parameters) {
            return 'The ' . $attribute . ' is too long';
        });
    }
}
