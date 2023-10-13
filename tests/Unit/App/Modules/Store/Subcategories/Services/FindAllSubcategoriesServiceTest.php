<?php

namespace Tests\Unit\App\Modules\Store\Subcategories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesFiltersDTO;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Modules\Store\Subcategories\Repositories\SubcategoriesRepository;
use App\Modules\Store\Subcategories\Services\FindAllSubcategoriesService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindAllSubcategoriesServiceTest extends TestCase
{
    private  MockObject|SubcategoriesRepositoryInterface $subcategoriesRepositoryMock;
    private  MockObject|SubcategoriesFiltersDTO $subcategoriesFiltersDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->subcategoriesRepositoryMock = $this->createMock(SubcategoriesRepository::class);
        $this->subcategoriesFiltersDtoMock = $this->createMock(SubcategoriesFiltersDTO::class);
    }

    public function getFindAllSubcategoriesService(): FindAllSubcategoriesService
    {
        return new FindAllSubcategoriesService(
            $this->subcategoriesRepositoryMock
        );
    }

    public function getSubcategories(): Collection
    {
        return Collection::make([
            [
                Subcategory::ID => Uuid::uuid4Generate(),
                Subcategory::NAME => 'test',
                Subcategory::DESCRIPTION => 'test',
            ]
        ]);
    }

    public function test_should_return_empty()
    {
        $findAllSubcategoriesService = $this->getFindAllSubcategoriesService();

        $findAllSubcategoriesService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_VIEW->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::empty());

        $subcategories = $findAllSubcategoriesService->execute($this->subcategoriesFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $subcategories);
    }

    public function test_should_return_categories_list()
    {
        $findAllSubcategoriesService = $this->getFindAllSubcategoriesService();

        $findAllSubcategoriesService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_VIEW->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findAll')
            ->willReturn($this->getSubcategories());

        $subcategories = $findAllSubcategoriesService->execute($this->subcategoriesFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $subcategories);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllSubcategoriesService = $this->getFindAllSubcategoriesService();

        $findAllSubcategoriesService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllSubcategoriesService->execute($this->subcategoriesFiltersDtoMock);
    }
}
