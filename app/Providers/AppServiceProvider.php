<?php

namespace App\Providers;

use App\Pagination\PathPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        $isLocalHost = !app()->runningInConsole() && in_array(request()->getHost(), ['127.0.0.1', 'localhost']);

        if (!$isLocalHost) {
            if (config('app.env') !== 'local' || isset($_SERVER['HTTPS']) || str_contains(config('app.url'), 'https')) {
                URL::forceScheme('https');
            }
        }

        /**
         * Macro: pathPaginate
         *
         * Usage: Model::query()->pathPaginate(15, url('admin/products/page'));
         *
         * Generates pagination URLs like /admin/products/page/2 instead of ?page=2.
         */
        Builder::macro('pathPaginate', function (int $perPage, string $basePath): PathPaginator {
            $page = (int) (request()->route('page') ?? 1);
            $model = $this->getModel();

            if ($model instanceof Model && ! Schema::hasTable($model->getTable())) {
                return new PathPaginator(collect([]), 0, $perPage, $page, [
                    'path' => $basePath,
                ]);
            }

            $total = $this->toBase()->getCountForPagination();
            $items = $this->forPage($page, $perPage)->get();

            return new PathPaginator($items, $total, $perPage, $page, [
                'path' => $basePath,
            ]);
        });
    }
}
