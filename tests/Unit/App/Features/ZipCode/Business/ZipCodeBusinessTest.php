<?php

namespace Tests\Unit\App\Features\ZipCode\Business;

use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Repositories\CityRepository;
use App\Features\ZipCode\Business\ZipCodeBusiness;
use App\Features\ZipCode\Http\Responses\ZipCodeResponse;
use App\Features\ZipCode\Services\AddressByZipCodeService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Unit\App\Resources\CitiesLists;
use Tests\Unit\App\Resources\ZipCodeLists;

class ZipCodeBusinessTest extends TestCase
{
    private MockObject|CityRepositoryInterface $cityRepositoryMock;
    private MockObject|AddressByZipCodeService $addressByZipCodeServiceMock;
    private MockObject|ZipCodeResponse         $zipCodeResponseMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cityRepositoryMock          = $this->createMock(CityRepository::class);
        $this->addressByZipCodeServiceMock = $this->createMock(AddressByZipCodeService::class);
        $this->zipCodeResponseMock         = $this->createMock(ZipCodeResponse::class);
    }

    public function zipCodeBusinessInstance(): ZipCodeBusiness
    {
        return new ZipCodeBusiness(
            $this->cityRepositoryMock,
            $this->addressByZipCodeServiceMock,
            $this->zipCodeResponseMock
        );
    }

    public function test_should_return_address_by_zip_code()
    {
        $zipCodeBusiness = $this->zipCodeBusinessInstance();

        $this
            ->addressByZipCodeServiceMock
            ->method('execute')
            ->willReturn(ZipCodeLists::getAddressByZipCode());

        $this
            ->cityRepositoryMock
            ->method('findByDescription')
            ->willReturn(CitiesLists::showCityById());

        $this
            ->cityRepositoryMock
            ->method('findByUF')
            ->willReturn(CitiesLists::getCities());

        $address = $zipCodeBusiness->findByZipCode('93052170');

        $this->assertInstanceOf(ZipCodeResponse::class, $address);
    }
}
