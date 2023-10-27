<?php

namespace Tests\Unit\App\Modules\Store\Departments\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\DTO\DepartmentsDTO;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Departments\Repositories\DepartmentsRepository;
use App\Modules\Store\Departments\Services\UpdateDepartmentService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateDepartmentServiceTest extends TestCase
{
    private  MockObject|DepartmentsRepositoryInterface $departmentsRepositoryMock;

    private MockObject|DepartmentsDTO $departmentsDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->departmentsRepositoryMock = $this->createMock(DepartmentsRepository::class);

        $this->departmentsDtoMock = $this->createMock(DepartmentsDTO::class);

        $this->setDto();
    }

    public function getUpdateDepartmentService(): UpdateDepartmentService
    {
        return new UpdateDepartmentService(
            $this->departmentsRepositoryMock,
        );
    }

    public function setDto(): void
    {
        $this->departmentsDtoMock->id = Uuid::uuid4Generate();
        $this->departmentsDtoMock->name = 'test';
        $this->departmentsDtoMock->description = 'test';
    }

    public function test_should_update_unique_department()
    {
        $updateDepartmentService = $this->getUpdateDepartmentService();

        $updateDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_UPDATE->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->departmentsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $created = $updateDepartmentService->execute($this->departmentsDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_return_exception_if_department_id_not_exists()
    {
        $updateDepartmentService = $this->getUpdateDepartmentService();

        $updateDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_UPDATE->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_NOT_FOUND));

        $updateDepartmentService->execute($this->departmentsDtoMock);
    }

    public function test_should_return_exception_if_department_name_already_exists()
    {
        $updateDepartmentService = $this->getUpdateDepartmentService();

        $updateDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_UPDATE->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findById')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this
            ->departmentsRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_NAME_ALREADY_EXISTS));

        $updateDepartmentService->execute($this->departmentsDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateDepartmentService = $this->getUpdateDepartmentService();

        $updateDepartmentService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateDepartmentService->execute($this->departmentsDtoMock);
    }
}
