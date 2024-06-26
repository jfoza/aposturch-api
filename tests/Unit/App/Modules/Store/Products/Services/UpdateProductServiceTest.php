<?php

namespace Tests\Unit\App\Modules\Store\Products\Services;

use App\Exceptions\AppException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\General\Images\Repositories\ImagesRepository;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsDTO;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsPersistenceRepository;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Products\Services\UpdateProductService;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateProductServiceTest extends TestCase
{
    private  MockObject|ProductsPersistenceRepositoryInterface $productsPersistenceRepositoryMock;
    private  MockObject|ProductsRepositoryInterface            $productsRepositoryMock;
    private  MockObject|CategoriesRepositoryInterface          $categoriesRepositoryMock;
    private  MockObject|ImagesRepositoryInterface              $imagesRepository;

    private  MockObject|ProductsDTO $productsDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productsPersistenceRepositoryMock = $this->createMock(ProductsPersistenceRepository::class);
        $this->productsRepositoryMock            = $this->createMock(ProductsRepository::class);
        $this->categoriesRepositoryMock          = $this->createMock(CategoriesRepository::class);
        $this->imagesRepository                  = $this->createMock(ImagesRepository::class);

        $this->productsDtoMock = $this->createMock(ProductsDTO::class);

        $this->productsDtoMock->imagesDTO = $this->createMock(ImagesDTO::class);
    }

    public function getUpdateProductService(): UpdateProductService
    {
        return new UpdateProductService(
            $this->productsPersistenceRepositoryMock,
            $this->productsRepositoryMock,
            $this->categoriesRepositoryMock,
            $this->imagesRepository
        );
    }

    public function populateDTO(): void
    {
        $this->productsDtoMock->id                 = Uuid::uuid4Generate();
        $this->productsDtoMock->productName        = 'test';
        $this->productsDtoMock->productDescription = 'test';
        $this->productsDtoMock->productCode        = 'LV0000';
        $this->productsDtoMock->value              = 50;
        $this->productsDtoMock->quantity           = 10;
        $this->productsDtoMock->balance            = 10;
        $this->productsDtoMock->categoriesId       = [];
        $this->productsDtoMock->imageLinks         = [];
        $this->productsDtoMock->highlightProduct   = false;
    }

    public function test_should_update_unique_product()
    {
        $updateProductService = $this->getUpdateProductService();

        $updateProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_UPDATE->value])
        );

        $this->populateDTO();

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

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
            ->method('save')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $updated = $updateProductService->execute($this->productsDtoMock);

        $this->assertIsObject($updated);
    }

    public function test_should_create_new_product_with_categories()
    {
        $updateProductService = $this->getUpdateProductService();

        $updateProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_UPDATE->value])
        );

        $this->populateDTO();

        $categoryId = Uuid::uuid4Generate();

        $this->productsDtoMock->categoriesId = [$categoryId];

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findByCode')
            ->willReturn(null);

        $this
            ->categoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([Category::ID => $categoryId])
                ])
            );

        $this
            ->productsPersistenceRepositoryMock
            ->method('save')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $updated = $updateProductService->execute($this->productsDtoMock);

        $this->assertIsObject($updated);
    }

    public function test_should_return_exception_if_product_id_not_exists()
    {
        $updateProductService = $this->getUpdateProductService();

        $updateProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_UPDATE->value])
        );

        $this->populateDTO();

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NOT_FOUND));

        $updateProductService->execute($this->productsDtoMock);
    }

    public function test_should_return_exception_if_balance_greater_than_the_amount()
    {
        $updateProductService = $this->getUpdateProductService();

        $updateProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_UPDATE->value])
        );

        $this->populateDTO();

        $this->productsDtoMock->quantity = 10;
        $this->productsDtoMock->balance  = 11;

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findByCode')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::BALANCE_IS_GREATER_THAN_THE_AMOUNT));

        $updateProductService->execute($this->productsDtoMock);
    }

    public function test_should_return_exception_if_product_name_already_exists()
    {
        $updateProductService = $this->getUpdateProductService();

        $updateProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_UPDATE->value])
        );

        $this->populateDTO();

        $categoryId = Uuid::uuid4Generate();

        $this->productsDtoMock->categoriesId = [$categoryId];

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NAME_ALREADY_EXISTS));

        $updateProductService->execute($this->productsDtoMock);
    }

    public function test_should_return_exception_if_product_code_already_exists()
    {
        $updateProductService = $this->getUpdateProductService();

        $updateProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_UPDATE->value])
        );

        $this->populateDTO();

        $categoryId = Uuid::uuid4Generate();

        $this->productsDtoMock->categoriesId = [$categoryId];

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

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

        $updateProductService->execute($this->productsDtoMock);
    }

    public function test_should_return_exception_if_category_id_not_exists()
    {
        $updateProductService = $this->getUpdateProductService();

        $updateProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_UPDATE->value])
        );

        $this->populateDTO();

        $this->productsDtoMock->categoriesId = [Uuid::uuid4Generate()];

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $this
            ->productsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findByCode')
            ->willReturn(null);

        $this
            ->categoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([Category::ID => Uuid::uuid4Generate()])
                ])
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_NOT_FOUND));

        $updateProductService->execute($this->productsDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateProductService = $this->getUpdateProductService();

        $updateProductService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateProductService->execute($this->productsDtoMock);
    }
}
