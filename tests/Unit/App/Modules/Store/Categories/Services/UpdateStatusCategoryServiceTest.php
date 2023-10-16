<?php

namespace Tests\Unit\App\Modules\Store\Categories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\UpdateStatusCategoryService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateStatusCategoryServiceTest extends TestCase
{
    private MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;

    private string $category1;
    private string $category2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoriesRepositoryMock = $this->createMock(CategoriesRepository::class);

        $this->category1 = Uuid::uuid4Generate();
        $this->category2 = Uuid::uuid4Generate();
    }

    public function getUpdateStatusCategoryService(): UpdateStatusCategoryService
    {
        return new UpdateStatusCategoryService(
            $this->categoriesRepositoryMock
        );
    }

    public function test_should_update_status_of_many_categories_id()
    {
        $updateStatusCategoryService = $this->getUpdateStatusCategoryService();

        $updateStatusCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_STATUS_UPDATE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([
                        Category::ID     => $this->category1,
                        Category::ACTIVE => false,
                    ]),
                    (object) ([
                        Category::ID     => $this->category2,
                        Category::ACTIVE => true,
                    ])
                ])
            );

        $updated = $updateStatusCategoryService->execute([$this->category1, $this->category2]);

        $this->assertInstanceOf(Collection::class, $updated);
    }

    public function test_should_return_exception_if_any_of_the_categories_are_not_found()
    {
        $updateStatusCategoryService = $this->getUpdateStatusCategoryService();

        $updateStatusCategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_STATUS_UPDATE->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([
                        Category::ID     => $this->category1,
                        Category::ACTIVE => false,
                    ]),
                    (object) ([
                        Category::ID     => Uuid::uuid4Generate(),
                        Category::ACTIVE => true,
                    ])
                ])
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_NOT_FOUND));

        $updateStatusCategoryService->execute([$this->category1, $this->category2]);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusCategoryService = $this->getUpdateStatusCategoryService();

        $updateStatusCategoryService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusCategoryService->execute([$this->category1, $this->category2]);
    }
}
