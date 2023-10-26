<?php

namespace Tests\Unit\App\Modules\Store\Products\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsDTO;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsPersistenceRepository;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Products\Services\CreateProductService;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Modules\Store\Subcategories\Repositories\SubcategoriesRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateProductServiceTest extends TestCase
{
    private  MockObject|ProductsPersistenceRepositoryInterface $productsPersistenceRepositoryMock;
    private  MockObject|ProductsRepositoryInterface            $productsRepositoryMock;
    private  MockObject|SubcategoriesRepositoryInterface       $subcategoriesRepositoryMock;

    private  MockObject|ProductsDTO $productsDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productsPersistenceRepositoryMock = $this->createMock(ProductsPersistenceRepository::class);
        $this->productsRepositoryMock            = $this->createMock(ProductsRepository::class);
        $this->subcategoriesRepositoryMock       = $this->createMock(SubcategoriesRepository::class);

        $this->productsDtoMock = $this->createMock(ProductsDTO::class);
    }

    public function getCreateProductService(): CreateProductService
    {
        return new CreateProductService(
            $this->productsPersistenceRepositoryMock,
            $this->productsRepositoryMock,
            $this->subcategoriesRepositoryMock,
        );
    }

    public function populateDTO(): void
    {
        $this->productsDtoMock->productName        = 'test';
        $this->productsDtoMock->productDescription = 'test';
        $this->productsDtoMock->productCode        = 'LV0000';
        $this->productsDtoMock->value              = 50;
        $this->productsDtoMock->quantity           = 10;
        $this->productsDtoMock->subcategoriesId    = [];
        $this->productsDtoMock->highlightProduct   = false;
    }

    public function test_should_create_new_product()
    {
        $createProductService = $this->getCreateProductService();

        $createProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_INSERT->value])
        );

        $this->populateDTO();

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findByCode')
            ->willReturn(null);

        $this
            ->productsPersistenceRepositoryMock
            ->method('create')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $created = $createProductService->execute($this->productsDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_create_new_product_with_subcategories()
    {
        $createProductService = $this->getCreateProductService();

        $createProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_INSERT->value])
        );

        $this->populateDTO();

        $subcategoryId = Uuid::uuid4Generate();

        $this->productsDtoMock->subcategoriesId = [$subcategoryId];

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findByCode')
            ->willReturn(null);

        $this
            ->subcategoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([Subcategory::ID => $subcategoryId])
                ])
            );

        $this
            ->productsPersistenceRepositoryMock
            ->method('create')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $created = $createProductService->execute($this->productsDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_return_exception_if_product_name_already_exists()
    {
        $createProductService = $this->getCreateProductService();

        $createProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_INSERT->value])
        );

        $this->populateDTO();

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NAME_ALREADY_EXISTS));

        $createProductService->execute($this->productsDtoMock);
    }

    public function test_should_return_exception_if_product_code_already_exists()
    {
        $createProductService = $this->getCreateProductService();

        $createProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_INSERT->value])
        );

        $this->populateDTO();

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findByCode')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_CODE_ALREADY_EXISTS));

        $createProductService->execute($this->productsDtoMock);
    }

    public function test_should_return_exception_if_subcategory_id_not_exists()
    {
        $createProductService = $this->getCreateProductService();

        $createProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_INSERT->value])
        );

        $this->populateDTO();

        $this->productsDtoMock->subcategoriesId = [Uuid::uuid4Generate()];

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findByCode')
            ->willReturn(null);

        $this
            ->subcategoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([Subcategory::ID => Uuid::uuid4Generate()])
                ])
            );
        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SUBCATEGORY_NOT_FOUND));

        $createProductService->execute($this->productsDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createProductService = $this->getCreateProductService();

        $createProductService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createProductService->execute($this->productsDtoMock);
    }
}
