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
use App\Modules\Store\Categories\Services\UpdateCategoryService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateCategoryServiceTest extends TestCase
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

    public function getUpdateCategoryService(): UpdateCategoryService
    {
        return new UpdateCategoryService(
            $this->departmentsRepositoryMock,
            $this->categoriesRepositoryMock,
            $this->productsRepositoryMock
        );
    }

    public function setDto(): void
    {
        $this->categoriesDtoMock->id           = Uuid::uuid4Generate();
        $this->categoriesDtoMock->departmentId = Uuid::uuid4Generate();
        $this->categoriesDtoMock->name         = 'test';
        $this->categoriesDtoMock->description  = 'test';
        $this->categoriesDtoMock->productsId   = [];
    }

    public function test_should_update_unique_category()
    {
        $updateCategoryService = $this->getUpdateCategoryService();

        $updateCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_UPDATE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('save')
            ->willReturn((object) ([
                Category::ID            => Uuid::uuid4Generate(),
                Category::DEPARTMENT_ID => Uuid::uuid4Generate(),
                Category::NAME          => 'test',
                Category::DESCRIPTION   => 'test',
            ]));

        $updated = $updateCategoryService->execute($this->categoriesDtoMock);

        $this->assertIsObject($updated);
    }

    public function test_should_update_unique_category_with_products()
    {
        $updateCategoryService = $this->getUpdateCategoryService();

        $updateCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_UPDATE->value])
        );

        $productId = Uuid::uuid4Generate();

        $this->categoriesDtoMock->productsId = [$productId];

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->productsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(Collection::make([
                (object)[Product::ID => $productId]
            ]));

        $this
            ->categoriesRepositoryMock
            ->method('save')
            ->willReturn((object) ([
                Category::ID            => Uuid::uuid4Generate(),
                Category::DEPARTMENT_ID => Uuid::uuid4Generate(),
                Category::NAME          => 'test',
                Category::DESCRIPTION   => 'test',
            ]));

        $updated = $updateCategoryService->execute($this->categoriesDtoMock);

        $this->assertIsObject($updated);
    }

    public function test_should_return_error_if_product_id_not_exists()
    {
        $updateCategoryService = $this->getUpdateCategoryService();

        $updateCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_UPDATE->value])
        );

        $this->categoriesDtoMock->productsId = [Uuid::uuid4Generate()];

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->productsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(Collection::make([
                (object)[Product::ID => Uuid::uuid4Generate()]
            ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PRODUCT_NOT_FOUND));

        $updateCategoryService->execute($this->categoriesDtoMock);
    }

    public function test_should_return_error_if_category_id_not_exists()
    {
        $updateCategoryService = $this->getUpdateCategoryService();

        $updateCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_UPDATE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_NOT_FOUND));

        $updateCategoryService->execute($this->categoriesDtoMock);
    }

    public function test_should_return_error_if_category_name_already_exists()
    {
        $updateCategoryService = $this->getUpdateCategoryService();

        $updateCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_UPDATE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_NAME_ALREADY_EXISTS));

        $updateCategoryService->execute($this->categoriesDtoMock);
    }

    public function test_should_return_error_if_department_id_not_exists()
    {
        $updateCategoryService = $this->getUpdateCategoryService();

        $updateCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_UPDATE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_NOT_FOUND));

        $updateCategoryService->execute($this->categoriesDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateCategoryService = $this->getUpdateCategoryService();

        $updateCategoryService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateCategoryService->execute($this->categoriesDtoMock);
    }
}
