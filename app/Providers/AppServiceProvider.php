<?php

namespace App\Providers;

use App\Container;
use App\Http\ErrorHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /** @var Container $app */
        $this->app->instance('errorHandler', function ($request, $response, $exception) {
            return (new ErrorHandler())->handle($request, $response, $exception);
        });

        $this->app->rebinding('notFoundHandler', function ($request, $response, $exception) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Not Found',
            ];
        });
    }
}