<?php

namespace Tests\Unit\App\Modules\Store\Products\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Products\Services\ShowByProductIdService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowByProductIdServiceTest extends TestCase
{
    private MockObject|ProductsRepositoryInterface $productsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productsRepositoryMock = $this->createMock(ProductsRepository::class);
    }

    public function getShowByProductIdService(): ShowByProductIdService
    {
        return new ShowByProductIdService(
            $this->productsRepositoryMock
        );
    }

    public function test_should_return_unique_product()
    {
        $showByProductIdService = $this->getShowByProductIdService();

        $showByProductIdService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value])
        );

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $products = $showByProductIdService->execute(Uuid::uuid4Generate());

        $this->assertIsObject($products);
    }

    public function test_should_return_exception_if_product_not_exists()
    {
        $showByProductIdService = $this->getShowByProductIdService();

        $showByProductIdService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value])
        );

        $this
            ->productsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NOT_FOUND));

        $showByProductIdService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showByProductIdService = $this->getShowByProductIdService();

        $showByProductIdService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showByProductIdService->execute(Uuid::uuid4Generate());
    }
}
