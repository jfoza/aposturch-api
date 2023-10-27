<?php

namespace Tests\Unit\App\Modules\Store\Categories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\FindByCategoryIdService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindByCategoryIdServiceTest extends TestCase
{
    private  MockObject|CategoriesRepositoryInterface $categoriesRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->categoriesRepositoryMock = $this->createMock(CategoriesRepository::class);
    }

    public function getFindByCategoryIdService(): FindByCategoryIdService
    {
        return new FindByCategoryIdService(
            $this->categoriesRepositoryMock
        );
    }

    public function getCategory(): object
    {
        return (object) ([
            Category::ID => Uuid::uuid4Generate(),
            Category::NAME => 'test',
            Category::DESCRIPTION => 'test',
        ]);
    }

    public function test_should_return_unique_category()
    {
        $findByCategoryIdService = $this->getFindByCategoryIdService();

        $findByCategoryIdService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_VIEW->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn($this->getCategory());

        $category = $findByCategoryIdService->execute(Uuid::uuid4Generate());

        $this->assertIsObject($category);
    }

    public function test_should_return_exception_if_category_not_exists()
    {
        $findByCategoryIdService = $this->getFindByCategoryIdService();

        $findByCategoryIdService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_CATEGORIES_VIEW->value])
        );

        $this
            ->categoriesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CATEGORY_NOT_FOUND));

        $findByCategoryIdService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findByCategoryIdService = $this->getFindByCategoryIdService();

        $findByCategoryIdService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findByCategoryIdService->execute(Uuid::uuid4Generate());
    }
}
