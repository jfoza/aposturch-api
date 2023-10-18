<?php

namespace Tests\Unit\App\Modules\Store\Subcategories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Modules\Store\Subcategories\Repositories\SubcategoriesRepository;
use App\Modules\Store\Subcategories\Services\UpdateSubcategoryService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateSubcategoryServiceTest extends TestCase
{
    private  MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;
    private  MockObject|SubcategoriesRepositoryInterface $subcategoriesRepositoryMock;
    private  MockObject|ProductsRepositoryInterface $productsRepositoryMock;

    private  MockObject|SubcategoriesDTO $subcategoriesDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoriesRepositoryMock    = $this->createMock(CategoriesRepository::class);
        $this->subcategoriesRepositoryMock = $this->createMock(SubcategoriesRepository::class);
        $this->productsRepositoryMock      = $this->createMock(ProductsRepository::class);

        $this->subcategoriesDtoMock = $this->createMock(SubcategoriesDTO::class);

        $this->setDto();
    }

    public function getUpdateSubcategoryService(): UpdateSubcategoryService
    {
        return new UpdateSubcategoryService(
            $this->categoriesRepositoryMock,
            $this->subcategoriesRepositoryMock,
            $this->productsRepositoryMock
        );
    }

    public function setDto(): void
    {
        $this->subcategoriesDtoMock->id          = Uuid::uuid4Generate();
        $this->subcategoriesDtoMock->categoryId  = Uuid::uuid4Generate();
        $this->subcategoriesDtoMock->name        = 'test';
        $this->subcategoriesDtoMock->description = 'test';
    }

    public function test_should_update_unique_subcategory()
    {
        $updateSubcategoryService = $this->getUpdateSubcategoryService();

        $updateSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_UPDATE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Subcategory::ID => Uuid::uuid4Generate() ]));

        $this
            ->subcategoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $updated = $updateSubcategoryService->execute($this->subcategoriesDtoMock);

        $this->assertIsObject($updated);
    }

    public function test_should_update_unique_subcategory_with_products()
    {
        $updateSubcategoryService = $this->getUpdateSubcategoryService();

        $updateSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_UPDATE->value])
        );

        $productId = Uuid::uuid4Generate();

        $this->subcategoriesDtoMock->productsId = [$productId];

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Subcategory::ID => Uuid::uuid4Generate() ]));

        $this
            ->subcategoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->productsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(Collection::make([
                (object)[Product::ID => $productId]
            ]));

        $this
            ->subcategoriesRepositoryMock
            ->method('save')
            ->willReturn((object) ([
                Subcategory::ID          => Uuid::uuid4Generate(),
                Subcategory::CATEGORY_ID => Uuid::uuid4Generate(),
                Subcategory::NAME        => 'test',
                Subcategory::DESCRIPTION => 'test',
            ]));

        $updated = $updateSubcategoryService->execute($this->subcategoriesDtoMock);

        $this->assertIsObject($updated);
    }

    public function test_should_return_error_if_product_id_not_exists()
    {
        $updateSubcategoryService = $this->getUpdateSubcategoryService();

        $updateSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_UPDATE->value])
        );

        $this->subcategoriesDtoMock->productsId = [Uuid::uuid4Generate()];

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Subcategory::ID => Uuid::uuid4Generate() ]));

        $this
            ->subcategoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->productsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(Collection::make([
                (object)[Product::ID => Uuid::uuid4Generate()]
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NOT_FOUND));

        $updateSubcategoryService->execute($this->subcategoriesDtoMock);
    }

    public function test_should_return_error_if_subcategory_id_not_exists()
    {
        $updateSubcategoryService = $this->getUpdateSubcategoryService();

        $updateSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_UPDATE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SUBCATEGORY_NOT_FOUND));

        $updateSubcategoryService->execute($this->subcategoriesDtoMock);
    }

    public function test_should_return_error_if_subcategory_name_already_exists()
    {
        $updateSubcategoryService = $this->getUpdateSubcategoryService();

        $updateSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_UPDATE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Subcategory::ID => Uuid::uuid4Generate() ]));

        $this
            ->subcategoriesRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([ Subcategory::ID => Uuid::uuid4Generate() ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SUBCATEGORY_NAME_ALREADY_EXISTS));

        $updateSubcategoryService->execute($this->subcategoriesDtoMock);
    }

    public function test_should_return_error_if_category_id_not_exists()
    {
        $updateSubcategoryService = $this->getUpdateSubcategoryService();

        $updateSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_UPDATE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Subcategory::ID => Uuid::uuid4Generate() ]));

        $this
            ->subcategoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_NOT_FOUND));

        $updateSubcategoryService->execute($this->subcategoriesDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateSubcategoryService = $this->getUpdateSubcategoryService();

        $updateSubcategoryService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateSubcategoryService->execute($this->subcategoriesDtoMock);
    }
}
