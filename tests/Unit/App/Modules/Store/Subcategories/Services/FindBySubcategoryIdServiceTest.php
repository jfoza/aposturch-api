<?php

namespace Tests\Unit\App\Modules\Store\Subcategories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Modules\Store\Subcategories\Repositories\SubcategoriesRepository;
use App\Modules\Store\Subcategories\Services\FindBySubcategoryIdService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindBySubcategoryIdServiceTest extends TestCase
{
    private  MockObject|SubcategoriesRepositoryInterface $subcategoriesRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->subcategoriesRepositoryMock = $this->createMock(SubcategoriesRepository::class);
    }

    public function getFindBySubcategoryIdService(): FindBySubcategoryIdService
    {
        return new FindBySubcategoryIdService(
            $this->subcategoriesRepositoryMock
        );
    }

    public function getSubcategory(): object
    {
        return (object) ([
            Subcategory::ID => Uuid::uuid4Generate(),
            Subcategory::NAME => 'test',
            Subcategory::DESCRIPTION => 'test',
        ]);
    }

    public function test_should_return_unique_category()
    {
        $findBySubcategoryIdService = $this->getFindBySubcategoryIdService();

        $findBySubcategoryIdService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_VIEW->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn($this->getSubcategory());

        $category = $findBySubcategoryIdService->execute(Uuid::uuid4Generate());

        $this->assertIsObject($category);
    }

    public function test_should_return_exception_if_category_not_exists()
    {
        $findBySubcategoryIdService = $this->getFindBySubcategoryIdService();

        $findBySubcategoryIdService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_VIEW->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SUBCATEGORY_NOT_FOUND));

        $findBySubcategoryIdService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findBySubcategoryIdService = $this->getFindBySubcategoryIdService();

        $findBySubcategoryIdService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findBySubcategoryIdService->execute(Uuid::uuid4Generate());
    }
}
