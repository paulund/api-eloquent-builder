<?php

namespace Paulund\ApiQueryBuilder;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        // Load routes
        if (app()->environment() === 'testing') {
            $this->loadRoutesFrom(__DIR__ . '/../tests/Mocks/Http/routes.php');
        }

        // Publish config
        $this->publishes([
            __DIR__ . '/config.php' => config_path('api-query-builder.php')
        ]);

        // Register macros
        Request::macro('hasFilters', function ($availableFilters) {
           return count(request()->getFilters($availableFilters)) > 0;
        });

        Request::macro('getFilters', function ($availableFilters) {
            return Arr::only($this->query(), $availableFilters);
        });

        Request::macro('hasIncludes', function () {
            return $this->has(config('api-query-builder.parameters.include', 'include'));
        });

        Request::macro('hasInclude', function ($include) {
            return array_search($include, request()->getIncludes([$include])) !== false;
        });

        Request::macro('getIncludes', function ($availableIncludes) {
            return collect(explode(',', $this->query(config('api-query-builder.parameters.include', 'include'))))
                ->filter(function ($include) use ($availableIncludes) {
                    return \in_array($include, $availableIncludes);
                })
                ->toArray();
        });
    }
}
