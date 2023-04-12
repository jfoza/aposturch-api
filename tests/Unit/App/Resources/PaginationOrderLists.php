<?php

namespace Tests\Unit\App\Resources;

class PaginationOrderLists
{
    CONST PAGE     = 'page';
    CONST PER_PAGE     = 'perPage';
    CONST COLUMN_ORDER = 'columnOrder';
    CONST COLUMN_NAME  = 'columnName';

    public static function mergePaginationOrderRules(array $filters = [], bool $required = false): array
    {
        $paginationRules = $required ? 'required|integer' : 'nullable|integer';

        $paginationOrderRules = [
            self::PAGE         => $paginationRules,
            self::PER_PAGE     => $paginationRules,
            self::COLUMN_ORDER => 'nullable|string|in:asc,desc',
            self::COLUMN_NAME  => 'nullable|string',
        ];

        return array_merge($paginationOrderRules, $filters);
    }

    public static function mergePaginationOrderAttributes(array $attributes = []): array
    {
        $paginationOrderAttributes = [
            self::PAGE         => 'Page',
            self::PER_PAGE     => 'Per Page',
            self::COLUMN_ORDER => 'Column Order',
            self::COLUMN_NAME  => 'Column Name',
        ];

        return array_merge($paginationOrderAttributes, $attributes);
    }
}
