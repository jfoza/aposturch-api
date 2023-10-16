<?php

namespace Tests\Unit\App\Modules\Store\Categories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\CreateCategoryService;
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

class CreateCategoryServiceTest extends TestCase
{
    private MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;
    private MockObject|SubcategoriesRepositoryInterface $subcategoriesRepositoryMock;

    private MockObject|CategoriesDTO $categoriesDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoriesRepositoryMock    = $this->createMock(CategoriesRepository::class);
        $this->subcategoriesRepositoryMock = $this->createMock(SubcategoriesRepository::class);

        $this->categoriesDtoMock = $this->createMock(CategoriesDTO::class);

        $this->setDto();
    }

    public function getCreateCategoryService(): CreateCategoryService
    {
        return new CreateCategoryService(
            $this->categoriesRepositoryMock,
            $this->subcategoriesRepositoryMock,
        );
    }

    public function setDto(): void
    {
        $this->categoriesDtoMock->name = 'test';
        $this->categoriesDtoMock->description = 'test';
    }

    public function test_should_create_unique_category()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $created = $createCategoryService->execute($this->categoriesDtoMock);

        $this->assertIsObject($created);
    }
    public function test_should_create_unique_category_with_subcategories()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $subcategory = Uuid::uuid4Generate();

        $this->categoriesDtoMock->subcategoriesId = [$subcategory];

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->subcategoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    [Subcategory::ID => $subcategory]
                ])
            );

        $this
            ->categoriesRepositoryMock
            ->method('create')
            ->willReturn((object)([Category::ID => Uuid::uuid4Generate()]));

        $created = $createCategoryService->execute($this->categoriesDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_return_exception_if_any_of_the_subcategories_are_not_found()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $this->categoriesDtoMock->subcategoriesId = [Uuid::uuid4Generate()];

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $this
            ->subcategoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    [Subcategory::ID => Uuid::uuid4Generate()]
                ])
            );

        $this
            ->categoriesRepositoryMock
            ->method('create')
            ->willReturn((object)([Category::ID => Uuid::uuid4Generate()]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SUBCATEGORY_NOT_FOUND));

        $createCategoryService->execute($this->categoriesDtoMock);
    }

    public function test_should_return_exception_if_category_name_already_exists()
    {
        $createCategoryService = $this->getCreateCategoryService();

        $createCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([ Category::ID => Uuid::uuid4Generate() ]));

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
