<?php

namespace App\Base\DTO;

use App\Base\Http\Pagination\PaginationOrder;

class FiltersDTO
{
    public function __construct(
        public PaginationOrder $paginationOrder,
    ) {}
}
