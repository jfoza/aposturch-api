<?php

namespace Tests\Feature\Resources\Modules\Churches;

use Ramsey\Uuid\Uuid;

trait ChurchesDataProviders
{
    public function formErrorsDataProvider(): array
    {
        return [
            'Name is empty' => [
                "name" => '',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'Invalid Phone value' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => 123,
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'Invalid Email value' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "invalid@",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'Invalid YouTube value' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => 123,
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'Invalid Facebook value' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => 123,
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'Invalid Instagram value' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => 123,
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'ZipCode empty' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'Address empty' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'Number Address empty' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'District empty' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "",
                "active" => true,
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'Invalid Active value' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => "68",
                "uf" => "RS",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'UF empty' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "",
                "cityId" => Uuid::uuid4()->toString(),
            ],
            'City Id empty' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => '',
            ],
            'Invalid city id' => [
                "name" => 'test',
                "responsibleMembers" => [
                    Uuid::uuid4()->toString()
                ],
                "phone" => "51999999999",
                "email" => "test@gmail.com",
                "youtube" => "",
                "facebook" => "",
                "instagram" => "",
                "zipCode" => "93320012",
                "address" => "Av. Nações Unidas",
                "numberAddress" => "2815",
                "complement" => "",
                "district" => "Rio Branco",
                "active" => true,
                "uf" => "RS",
                "cityId" => 'abc',
            ]
        ];
    }
}
