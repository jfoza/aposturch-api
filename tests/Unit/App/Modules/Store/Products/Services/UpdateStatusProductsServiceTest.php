<?php

namespace Tests\Unit\App\Modules\Store\Products\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsPersistenceRepository;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Products\Services\UpdateStatusProductsService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateStatusProductsServiceTest extends TestCase
{
    private  MockObject|ProductsPersistenceRepositoryInterface $productsPersistenceRepositoryMock;
    private  MockObject|ProductsRepositoryInterface $productsRepositoryMock;

    private string $productId1;
    private string $productId2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productsPersistenceRepositoryMock = $this->createMock(ProductsPersistenceRepository::class);
        $this->productsRepositoryMock            = $this->createMock(ProductsRepository::class);

        $this->productId1 = Uuid::uuid4Generate();
        $this->productId2 = Uuid::uuid4Generate();
    }

    public function getUpdateStatusProductsService(): UpdateStatusProductsService
    {
        return new UpdateStatusProductsService(
            $this->productsRepositoryMock,
            $this->productsPersistenceRepositoryMock,
        );
    }

    public function test_should_update_status_products()
    {
        $updateStatusProductsService = $this->getUpdateStatusProductsService();

        $updateStatusProductsService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_STATUS_UPDATE->value])
        );

        $this
            ->productsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([
                        Product::ID     => $this->productId1,
                        Product::ACTIVE => false,
                    ]),
                    (object) ([
                        Product::ID     => $this->productId2,
                        Product::ACTIVE => true,
                    ])
                ])
            );

        $updated = $updateStatusProductsService->execute([$this->productId1, $this->productId2]);

        $this->assertInstanceOf(Collection::class, $updated);
    }

    public function test_should_return_exception_if_any_of_the_products_are_not_found()
    {
        $updateStatusProductsService = $this->getUpdateStatusProductsService();

        $updateStatusProductsService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_STATUS_UPDATE->value])
        );

        $this
            ->productsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([
                        Product::ID     => $this->productId1,
                        Product::ACTIVE => false,
                    ]),
                    (object) ([
                        Product::ID     => Uuid::uuid4Generate(),
                        Product::ACTIVE => true,
                    ])
                ])
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NOT_FOUND));

        $updateStatusProductsService->execute([$this->productId1, $this->productId2]);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusProductsService = $this->getUpdateStatusProductsService();

        $updateStatusProductsService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusProductsService->execute([$this->productId1, $this->productId2]);
    }
}
