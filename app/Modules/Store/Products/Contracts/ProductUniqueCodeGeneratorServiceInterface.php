<?php

namespace App\Modules\Store\Products\Contracts;

interface ProductUniqueCodeGeneratorServiceInterface
{
    public function execute(): array;
}
