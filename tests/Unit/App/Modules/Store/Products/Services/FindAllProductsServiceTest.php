<?php

namespace Tests\Unit\App\Modules\Store\Products\Services;

use App\Base\Http\Pagination\PaginationOrder;
use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Products\Services\FindAllProductsService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindAllProductsServiceTest extends TestCase
{
    private MockObject|ProductsRepositoryInterface $productsRepositoryMock;
    private MockObject|ProductsFiltersDTO $productsFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productsRepositoryMock = $this->createMock(ProductsRepository::class);
        $this->productsFiltersDtoMock = $this->createMock(ProductsFiltersDTO::class);
    }

    public function getFindAllProductsService(): FindAllProductsService
    {
        return new FindAllProductsService($this->productsRepositoryMock);
    }

    public function getProducts(): Collection
    {
        return Collection::make([
            [
                Product::ID,
                Product::PRODUCT_NAME,
            ]
        ]);
    }

    public function getPaginatedProductsList(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $this->getProducts(),
            10,
            10,
        );
    }

    public function test_should_return_products_list()
    {
        $findAllProductsService = $this->getFindAllProductsService();

        $findAllProductsService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value])
        );

        $this
            ->productsRepositoryMock
            ->method('findAll')
            ->willReturn($this->getProducts());

        $products = $findAllProductsService->execute($this->productsFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $products);
    }

    public function test_should_return_paginated_products_list()
    {
        $findAllProductsService = $this->getFindAllProductsService();

        $findAllProductsService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value])
        );

        $this->productsFiltersDtoMock->paginationOrder = new PaginationOrder();

        $this->productsFiltersDtoMock->paginationOrder->setPage(1);
        $this->productsFiltersDtoMock->paginationOrder->setPerPage(10);
        $this->productsFiltersDtoMock->paginationOrder->setColumnOrder(Product::PRODUCT_NAME);
        $this->productsFiltersDtoMock->paginationOrder->setColumnOrder('asc');

        $this
            ->productsRepositoryMock
            ->method('findAll')
            ->willReturn($this->getPaginatedProductsList());

        $products = $findAllProductsService->execute($this->productsFiltersDtoMock);

        $this->assertInstanceOf(LengthAwarePaginatorContract::class, $products);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllProductsService = $this->getFindAllProductsService();

        $findAllProductsService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllProductsService->execute($this->productsFiltersDtoMock);
    }
}
