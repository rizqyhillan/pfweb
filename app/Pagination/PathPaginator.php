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

        $url = rtrim($this->path(), '/').'/'.$page;

        if (count($this->query) > 0) {
            $url .= '?'.http_build_query($this->query, '', '&', PHP_QUERY_RFC3986);
        }

        return $url.$this->buildFragment();
    }
}
