<?php

namespace App\Features\General\Images\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\Repositories\ImagesRepository;

class ImagesProvider extends AbstractServiceProvider
{
    public array $bindings = [
        ImagesRepositoryInterface::class => ImagesRepository::class,
    ];
}
