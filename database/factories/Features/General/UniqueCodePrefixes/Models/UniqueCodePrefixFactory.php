<?php

namespace Database\Factories\Features\General\UniqueCodePrefixes\Models;

use App\Features\General\UniqueCodePrefixes\Models\UniqueCodePrefix;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class UniqueCodePrefixFactory extends Factory
{
    protected $model = UniqueCodePrefix::class;

    public function definition(): array
    {
        return [
            UniqueCodePrefix::PREFIX => strtoupper(RandomStringHelper::alphaGenerate(2)),
            UniqueCodePrefix::ACTIVE => true,
        ];
    }
}
