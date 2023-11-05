<?php

namespace App\Features\General\UniqueCodePrefixes\Contracts;

interface UniqueCodeGeneratorBusinessInterface
{
    public function handle(string $uniqueCodeType): array;
}
