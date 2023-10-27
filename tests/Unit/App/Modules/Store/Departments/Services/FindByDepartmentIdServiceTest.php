<?php

namespace Tests\Unit\App\Modules\Store\Departments\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Departments\Repositories\DepartmentsRepository;
use App\Modules\Store\Departments\Services\FindByDepartmentIdService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindByDepartmentIdServiceTest extends TestCase
{
    private  MockObject|DepartmentsRepositoryInterface $departmentsRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->departmentsRepositoryMock = $this->createMock(DepartmentsRepository::class);
    }

    public function getFindByDepartmentIdService(): FindByDepartmentIdService
    {
        return new FindByDepartmentIdService(
            $this->departmentsRepositoryMock
        );
    }

    public function getDepartment(): object
    {
        return (object) ([
            Department::ID => Uuid::uuid4Generate(),
            Department::NAME => 'test',
            Department::DESCRIPTION => 'test',
        ]);
    }

    public function test_should_return_unique_department()
    {
        $findByDepartmentIdService = $this->getFindByDepartmentIdService();

        $findByDepartmentIdService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_VIEW->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn($this->getDepartment());

        $department = $findByDepartmentIdService->execute(Uuid::uuid4Generate());

        $this->assertIsObject($department);
    }

    public function test_should_return_exception_if_department_not_exists()
    {
        $findByDepartmentIdService = $this->getFindByDepartmentIdService();

        $findByDepartmentIdService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_VIEW->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_NOT_FOUND));

        $findByDepartmentIdService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findByDepartmentIdService = $this->getFindByDepartmentIdService();

        $findByDepartmentIdService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findByDepartmentIdService->execute(Uuid::uuid4Generate());
    }
}
