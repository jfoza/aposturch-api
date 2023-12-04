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
use App\Modules\Store\Products\Services\CreateProductService;
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

class CreateProductServiceTest extends TestCase
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

    public function getCreateProductService(): CreateProductService
    {
        return new CreateProductService(
            $this->productsPersistenceRepositoryMock,
            $this->productsRepositoryMock,
            $this->categoriesRepositoryMock,
            $this->imagesRepository
        );
    }

    public function populateDTO(): void
    {
        $this->productsDtoMock->productName        = 'test';
        $this->productsDtoMock->productDescription = 'test';
        $this->productsDtoMock->productCode        = 'LV0000';
        $this->productsDtoMock->value              = 50;
        $this->productsDtoMock->quantity           = 10;
        $this->productsDtoMock->categoriesId       = [];
        $this->productsDtoMock->imageLinks         = [];
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

    public function test_should_create_new_product_with_categories()
    {
        $createProductService = $this->getCreateProductService();

        $createProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_INSERT->value])
        );

        $this->populateDTO();

        $categoryId = Uuid::uuid4Generate();

        $this->productsDtoMock->categoriesId = [$categoryId];

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

    public function test_should_return_exception_if_category_id_not_exists()
    {
        $createProductService = $this->getCreateProductService();

        $createProductService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_INSERT->value])
        );

        $this->populateDTO();

        $this->productsDtoMock->categoriesId = [Uuid::uuid4Generate()];

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
