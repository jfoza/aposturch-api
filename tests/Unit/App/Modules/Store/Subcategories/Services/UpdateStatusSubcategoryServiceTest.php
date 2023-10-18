<?php

namespace Tests\Unit\App\Modules\Store\Subcategories\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Modules\Store\Subcategories\Repositories\SubcategoriesRepository;
use App\Modules\Store\Subcategories\Services\UpdateStatusSubcategoriesService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateStatusSubcategoryServiceTest extends TestCase
{
    private MockObject|SubcategoriesRepositoryInterface $subcategoriesRepositoryMock;

    private string $subcategory1;
    private string $subcategory2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subcategoriesRepositoryMock = $this->createMock(SubcategoriesRepository::class);

        $this->subcategory1 = Uuid::uuid4Generate();
        $this->subcategory2 = Uuid::uuid4Generate();
    }

    public function getUpdateStatusSubcategoryService(): UpdateStatusSubcategoriesService
    {
        return new UpdateStatusSubcategoriesService(
            $this->subcategoriesRepositoryMock
        );
    }

    public function test_should_update_status_of_many_subcategories_id()
    {
        $updateStatusSubcategoryService = $this->getUpdateStatusSubcategoryService();

        $updateStatusSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_STATUS_UPDATE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([
                        Subcategory::ID     => $this->subcategory1,
                        Subcategory::ACTIVE => false,
                    ]),
                    (object) ([
                        Subcategory::ID     => $this->subcategory2,
                        Subcategory::ACTIVE => true,
                    ])
                ])
            );

        $updated = $updateStatusSubcategoryService->execute([$this->subcategory1, $this->subcategory2]);

        $this->assertInstanceOf(Collection::class, $updated);
    }

    public function test_should_return_exception_if_any_of_the_subcategories_are_not_found()
    {
        $updateStatusSubcategoryService = $this->getUpdateStatusSubcategoryService();

        $updateStatusSubcategoryService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_SUBCATEGORIES_STATUS_UPDATE->value])
        );

        $this
            ->subcategoriesRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([
                        Subcategory::ID     => $this->subcategory1,
                        Subcategory::ACTIVE => false,
                    ]),
                    (object) ([
                        Subcategory::ID     => Uuid::uuid4Generate(),
                        Subcategory::ACTIVE => true,
                    ])
                ])
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::SUBCATEGORY_NOT_FOUND));

        $updateStatusSubcategoryService->execute([$this->subcategory1, $this->subcategory2]);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusSubcategoryService = $this->getUpdateStatusSubcategoryService();

        $updateStatusSubcategoryService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusSubcategoryService->execute([$this->subcategory1, $this->subcategory2]);
    }
}
