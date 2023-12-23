<?php

namespace Tests\Unit\App\Modules\Store\Products\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Products\Services\ShowByProductUniqueNameService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowByProductUniqueNameServiceTest extends TestCase
{
    private MockObject|ProductsRepositoryInterface $productsRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productsRepositoryMock = $this->createMock(ProductsRepository::class);
    }

    public function getShowByProductUniqueNameService(): ShowByProductUniqueNameService
    {
        return new ShowByProductUniqueNameService(
            $this->productsRepositoryMock
        );
    }

    public function test_should_return_unique_product()
    {
        $showByProductUniqueNameService = $this->getShowByProductUniqueNameService();

        $showByProductUniqueNameService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value])
        );

        $this
            ->productsRepositoryMock
            ->method('findByUniqueName')
            ->willReturn((object) ([Product::ID => Uuid::uuid4Generate()]));

        $products = $showByProductUniqueNameService->execute('unique-name');

        $this->assertIsObject($products);
    }

    public function test_should_return_exception_if_product_not_exists()
    {
        $showByProductUniqueNameService = $this->getShowByProductUniqueNameService();

        $showByProductUniqueNameService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value])
        );

        $this
            ->productsRepositoryMock
            ->method('findByUniqueName')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NOT_FOUND));

        $showByProductUniqueNameService->execute('unique-name');
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showByProductUniqueNameService = $this->getShowByProductUniqueNameService();

        $showByProductUniqueNameService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showByProductUniqueNameService->execute('unique-name');
    }
}
