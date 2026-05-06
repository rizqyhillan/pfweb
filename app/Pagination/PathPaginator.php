<?php

namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Paginator that generates path-based URLs like /page/2
 * instead of query-string URLs like ?page=2.
 */
class PathPaginator extends LengthAwarePaginator
{
    public function url($page): string
    {
        if ($page <= 0) {
            $page = 1;
        }

        return rtrim($this->path(), '/').'/'.$page.$this->buildFragment();
    }
}
