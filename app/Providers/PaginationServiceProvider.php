<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Slim\Http\Request;

class PaginationServiceProvider extends ServiceProvider
{
    public function register()
    {
        Paginator::currentPathResolver(function () {
            /** @var Request $request */
            return $this->app['request']->getUri()->getPath();
        });

        Paginator::currentPageResolver(function ($pageName = 'page') {
            $page = $this->app['request']->getParam($pageName);
            if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
                return (int) $page;
            }

            return 1;
        });
    }
}