<?php

namespace Tests\Unit\App\Modules\Store\Categories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Departments\Repositories\DepartmentsRepository;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\CreateCategoryService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateCategoryServiceTest extends TestCase
{
    private  MockObject|DepartmentsRepositoryInterface $departmentsRepositoryMock;
    private  MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;
    private  MockObject|ProductsRepositoryInterface $productsRepositoryMock;
    private  MockObject|CategoriesDTO $categoriesDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->departmentsRepositoryMock = $this->createMock(DepartmentsRepository::class);
        $this->categoriesRepositoryMock  = $this->createMock(CategoriesRepository::class);
        $this->productsRepositoryMock    = $this->createMock(ProductsRepository::class);

        $this->categoriesDtoMock = $this->createMock(CategoriesDTO::class);

        $this->setDto();
    }

    public function getCreateCategoryService(): CreateCategoryService
    {
        return new CreateCategoryService(
            $this->departmentsRepositoryMock,
            $this->categoriesRepositoryMock,
            $this->productsRepositoryMock
        );
    }

    public function setDto(): void
    {
        $this->categoriesDtoMock->departmentId = Uuid::uuid4Generate();
        $this->categoriesDtoMock->name         = 'test';
        $this->categoriesDtoMock->description  = 'test';
    }

    public function test_should_create_unique_category()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $created = $createCategoryService->execute($this->categoriesDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_create_unique_category_with_products()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $productId = Uuid::uuid4Generate();

        $this->categoriesDtoMock->productsId = [$productId];

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(Collection::make([
                (object)[Product::ID => $productId]
            ]));

        $this
            ->categoriesRepositoryMock
            ->method('create')
            ->willReturn((object) ([
                Category::ID            => Uuid::uuid4Generate(),
                Category::DEPARTMENT_ID => Uuid::uuid4Generate(),
                Category::NAME          => 'test',
                Category::DESCRIPTION   => 'test',
            ]));

        $created = $createCategoryService->execute($this->categoriesDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_return_error_if_product_id_not_exists()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $this->categoriesDtoMock->productsId = [Uuid::uuid4Generate()];

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->productsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(Collection::make([
                (object)[Product::ID => Uuid::uuid4Generate()]
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NOT_FOUND));

        $createCategoryService->execute($this->categoriesDtoMock);
    }

    public function test_should_return_error_if_department_id_not_exists()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_NOT_FOUND));

        $createCategoryService->execute($this->categoriesDtoMock);
    }

    public function test_should_return_error_if_category_name_already_exists()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_NAME_ALREADY_EXISTS));

        $createCategoryService->execute($this->categoriesDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createCategoryService->execute($this->categoriesDtoMock);
    }
}
