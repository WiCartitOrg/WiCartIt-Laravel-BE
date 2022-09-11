<?php

namespace App\Services\Traits\Utilities;

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

trait PaginateCustomCollection
{

    public function Paginate(Collection $results, int $pageSize): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage('page');
        
        $total = $results->count();

        return $this->_paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private static function _paginator($items, $total, $perPage, $currentPage, $options): LengthAwarePaginator
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}