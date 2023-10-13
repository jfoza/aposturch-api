<?php

namespace Tests\Unit\App\Modules\Store\Categories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\CreateCategoryService;
use App\Modules\Store\Categories\Services\UpdateCategoryService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateCategoryServiceTest extends TestCase
{
    private MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;
    private MockObject|CategoriesDTO $categoriesDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoriesRepositoryMock = $this->createMock(CategoriesRepository::class);
        $this->categoriesDtoMock        = $this->createMock(CategoriesDTO::class);

        $this->setDto();
    }

    public function getUpdateCategoryService(): UpdateCategoryService
    {
        return new UpdateCategoryService(
            $this->categoriesRepositoryMock,
        );
    }

    public function setDto(): void
    {
        $this->categoriesDtoMock->id = Uuid::uuid4Generate();
        $this->categoriesDtoMock->name = 'test';
        $this->categoriesDtoMock->description = 'test';
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

        $created = $updateCategoryService->execute($this->categoriesDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_return_exception_if_category_id_not_exists()
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

    public function test_should_return_exception_if_category_name_already_exists()
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
