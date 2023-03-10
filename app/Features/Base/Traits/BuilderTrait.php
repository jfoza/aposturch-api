<?php

namespace App\Features\Base\Traits;

use App\Features\Base\Http\Pagination\PaginationOrder;
use App\Features\Base\Http\Requests\FormRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;

trait BuilderTrait
{
    public function paginateOrGet(
        QueryBuilder|EloquentBuilder $builder,
        PaginationOrder $paginationOrder
    ): LengthAwarePaginator|Collection
    {
        if(is_null($paginationOrder->getPage())) {
            return $builder->get();
        }

        return $builder->paginate(
            $paginationOrder->getPerPage(),
            ['*'],
            FormRequest::PAGE,
            $paginationOrder->getPage(),
        );
    }

    public function paginate(
        QueryBuilder|EloquentBuilder $builder,
        PaginationOrder $paginationOrder
    ): LengthAwarePaginator|Collection
    {
        return $builder->paginate(
            $paginationOrder->getPerPage(),
            ['*'],
            FormRequest::PAGE,
            $paginationOrder->getPage(),
        );
    }
}
