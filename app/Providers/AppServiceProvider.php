<?php

namespace App\Providers;

use App\Pagination\PathPaginator;
use Illuminate\Database\Eloquent\Builder;
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
        /**
         * Macro: pathPaginate
         *
         * Usage: Model::query()->pathPaginate(15, url('admin/products/page'));
         *
         * Generates pagination URLs like /admin/products/page/2 instead of ?page=2.
         */
        Builder::macro('pathPaginate', function (int $perPage, string $basePath): PathPaginator {
            $page = (int) (request()->route('page') ?? 1);
            $total = $this->toBase()->getCountForPagination();
            $items = $this->forPage($page, $perPage)->get();

            return new PathPaginator($items, $total, $perPage, $page, [
                'path' => $basePath,
            ]);
        });
    }
}
