<?php

namespace App\Features\Base\Views;

use Illuminate\Database\Query\Builder;

abstract class View
{
    public abstract static function viewName(): string;
    public abstract static function getView(): Builder;
}
