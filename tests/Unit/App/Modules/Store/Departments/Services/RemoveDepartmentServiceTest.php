<?php

namespace Tests\Unit\App\Modules\Store\Departments\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Departments\Repositories\DepartmentsRepository;
use App\Modules\Store\Departments\Services\RemoveDepartmentService;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Repositories\SubcategoriesRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RemoveDepartmentServiceTest extends TestCase
{
    private  MockObject|DepartmentsRepositoryInterface $departmentsRepositoryMock;
    private  MockObject|SubcategoriesRepositoryInterface $subcategoriesRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->departmentsRepositoryMock = $this->createMock(DepartmentsRepository::class);
        $this->subcategoriesRepositoryMock = $this->createMock(SubcategoriesRepository::class);
    }

    public function getRemoveDepartmentService(): RemoveDepartmentService
    {
        return new RemoveDepartmentService(
            $this->departmentsRepositoryMock,
            $this->subcategoriesRepositoryMock,
        );
    }

    public function test_should_remove_unique_department()
    {
        $removeDepartmentService = $this->getRemoveDepartmentService();

        $removeDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_DELETE->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->subcategoriesRepositoryMock
            ->method('findByDepartment')
            ->willReturn(Collection::empty());

        $removeDepartmentService->execute(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_department_has_subcategories()
    {
        $removeDepartmentService = $this->getRemoveDepartmentService();

        $removeDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_DELETE->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->subcategoriesRepositoryMock
            ->method('findByDepartment')
            ->willReturn(
                Collection::make([
                    [Department::ID => Uuid::uuid4Generate()]
                ])
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_HAS_SUBCATEGORIES));

        $removeDepartmentService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_department_not_exists()
    {
        $removeDepartmentService = $this->getRemoveDepartmentService();

        $removeDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_DELETE->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_NOT_FOUND));

        $removeDepartmentService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeDepartmentService = $this->getRemoveDepartmentService();

        $removeDepartmentService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeDepartmentService->execute(Uuid::uuid4Generate());
    }
}
