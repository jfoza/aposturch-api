<?php

namespace Tests\Unit\App\Modules\Store\Subcategories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Repositories\SubcategoriesRepository;
use App\Modules\Store\Subcategories\Services\RemoveSubcategoryService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RemoveSubCategoryServiceTest extends TestCase
{
    private  MockObject|SubcategoriesRepositoryInterface $subcategoriesRepositoryMock;
    private  MockObject|ProductsRepositoryInterface $productsRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->subcategoriesRepositoryMock = $this->createMock(SubcategoriesRepository::class);
        $this->productsRepositoryMock      = $this->createMock(ProductsRepository::class);
    }

    public function getRemoveSubcategoryService(): RemoveSubcategoryService
    {
        return new RemoveSubcategoryService(
            $this->subcategoriesRepositoryMock,
            $this->productsRepositoryMock
        );
    }

    public function test_should_remove_unique_subcategory()
    {
        $removeSubcategoryService = $this->getRemoveSubcategoryService();

        $removeSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_DELETE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->productsRepositoryMock
            ->method('findBySubcategory')
            ->willReturn(Collection::empty());

        $removeSubcategoryService->execute(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_subcategory_not_exists()
    {
        $removeSubcategoryService = $this->getRemoveSubcategoryService();

        $removeSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_DELETE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SUBCATEGORY_NOT_FOUND));

        $removeSubcategoryService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_subcategory_has_products()
    {
        $removeSubcategoryService = $this->getRemoveSubcategoryService();

        $removeSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_DELETE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->productsRepositoryMock
            ->method('findBySubcategory')
            ->willReturn(Collection::make([
                [Product::ID => Uuid::uuid4Generate()]
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SUBCATEGORY_HAS_PRODUCTS));

        $removeSubcategoryService->execute(Uuid::uuid4Generate());
    }


    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeSubcategoryService = $this->getRemoveSubcategoryService();

        $removeSubcategoryService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeSubcategoryService->execute(Uuid::uuid4Generate());
    }
}
