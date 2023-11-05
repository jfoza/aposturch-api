<?php

namespace Tests\Unit\App\Modules\Store\Categories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\RemoveCategoryService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RemoveCategoryServiceTest extends TestCase
{
    private  MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;
    private  MockObject|ProductsRepositoryInterface $productsRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoriesRepositoryMock = $this->createMock(CategoriesRepository::class);
        $this->productsRepositoryMock   = $this->createMock(ProductsRepository::class);
    }

    public function getRemoveCategoryService(): RemoveCategoryService
    {
        return new RemoveCategoryService(
            $this->categoriesRepositoryMock,
            $this->productsRepositoryMock
        );
    }

    public function test_should_remove_unique_category()
    {
        $removeCategoryService = $this->getRemoveCategoryService();

        $removeCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_DELETE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->productsRepositoryMock
            ->method('findByCategory')
            ->willReturn(Collection::empty());

        $removeCategoryService->execute(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_category_not_exists()
    {
        $removeCategoryService = $this->getRemoveCategoryService();

        $removeCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_DELETE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_NOT_FOUND));

        $removeCategoryService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_category_has_products()
    {
        $removeCategoryService = $this->getRemoveCategoryService();

        $removeCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_DELETE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->productsRepositoryMock
            ->method('findByCategory')
            ->willReturn(Collection::make([
                [Product::ID => Uuid::uuid4Generate()]
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_HAS_PRODUCTS));

        $removeCategoryService->execute(Uuid::uuid4Generate());
    }


    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeCategoryService = $this->getRemoveCategoryService();

        $removeCategoryService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeCategoryService->execute(Uuid::uuid4Generate());
    }
}
