<?php

namespace Database\Factories\Modules\Membership\Church\Models;

use App\Features\City\Cities\Infra\Models\City;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChurchFactory extends Factory
{
    protected $model = Church::class;

    public function definition(): array
    {
        $name = RandomStringHelper::alnumGenerate();

        $city = City::where(City::DESCRIPTION, 'Novo Hamburgo')->first();

        return [
            Church::NAME           => $name,
            Church::UNIQUE_NAME    => $name,
            Church::PHONE          => '51999999999',
            Church::EMAIL          => $name.'@email.com',
            Church::YOUTUBE        => '',
            Church::FACEBOOK       => '',
            Church::INSTAGRAM      => '',
            Church::ZIP_CODE       => '99999999',
            Church::ADDRESS        => 'test',
            Church::NUMBER_ADDRESS => '21',
            Church::COMPLEMENT     => '',
            Church::DISTRICT       => 'test',
            Church::UF             => 'RS',
            Church::CITY_ID        => $city->id,
            Church::ACTIVE         => true
        ];
    }
}
