<?php

namespace Tests\Unit\App\Modules\Store\Categories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\DTO\CategoriesFiltersDTO;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\FindAllCategoriesService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindAllCategoriesServiceTest extends TestCase
{
    private  MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;
    private  MockObject|CategoriesFiltersDTO $categoriesFiltersDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoriesRepositoryMock = $this->createMock(CategoriesRepository::class);
        $this->categoriesFiltersDtoMock = $this->createMock(CategoriesFiltersDTO::class);
    }

    public function getFindAllCategoriesService(): FindAllCategoriesService
    {
        return new FindAllCategoriesService(
            $this->categoriesRepositoryMock
        );
    }

    public function getCategories(): Collection
    {
        return Collection::make([
            [
                Category::ID => Uuid::uuid4Generate(),
                Category::NAME => 'test',
                Category::DESCRIPTION => 'test',
            ]
        ]);
    }

    public function test_should_return_empty()
    {
        $findAllCategoriesService = $this->getFindAllCategoriesService();

        $findAllCategoriesService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_VIEW->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::empty());

        $categories = $findAllCategoriesService->execute($this->categoriesFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $categories);
    }

    public function test_should_return_categories_list()
    {
        $findAllCategoriesService = $this->getFindAllCategoriesService();

        $findAllCategoriesService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_VIEW->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findAll')
            ->willReturn($this->getCategories());

        $categories = $findAllCategoriesService->execute($this->categoriesFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $categories);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllCategoriesService = $this->getFindAllCategoriesService();

        $findAllCategoriesService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllCategoriesService->execute($this->categoriesFiltersDtoMock);
    }
}
