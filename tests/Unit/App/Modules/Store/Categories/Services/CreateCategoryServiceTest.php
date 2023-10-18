<?php

namespace Tests\Unit\App\Modules\Store\Categories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\CreateCategoryService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateCategoryServiceTest extends TestCase
{
    private MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;

    private MockObject|CategoriesDTO $categoriesDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoriesRepositoryMock    = $this->createMock(CategoriesRepository::class);

        $this->categoriesDtoMock = $this->createMock(CategoriesDTO::class);

        $this->setDto();
    }

    public function getCreateCategoryService(): CreateCategoryService
    {
        return new CreateCategoryService(
            $this->categoriesRepositoryMock,
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
