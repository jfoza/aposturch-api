<?php

namespace Tests\Unit\App\Modules\Store\Departments\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\DTO\DepartmentsDTO;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Departments\Repositories\DepartmentsRepository;
use App\Modules\Store\Departments\Services\CreateDepartmentService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateDepartmentServiceTest extends TestCase
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

    public function getCreateDepartmentService(): CreateDepartmentService
    {
        return new CreateDepartmentService(
            $this->departmentsRepositoryMock,
        );
    }

    public function setDto(): void
    {
        $this->departmentsDtoMock->name = 'test';
        $this->departmentsDtoMock->description = 'test';
    }

    public function test_should_create_unique_department()
    {
        $createDepartmentService = $this->getCreateDepartmentService();

        $createDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_INSERT->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findByName')
            ->willReturn(null);

        $created = $createDepartmentService->execute($this->departmentsDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_return_exception_if_department_name_already_exists()
    {
        $createDepartmentService = $this->getCreateDepartmentService();

        $createDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_INSERT->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findByName')
            ->willReturn((object) ([ Department::ID => Uuid::uuid4Generate() ]));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_NAME_ALREADY_EXISTS));

        $createDepartmentService->execute($this->departmentsDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createDepartmentService = $this->getCreateDepartmentService();

        $createDepartmentService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createDepartmentService->execute($this->departmentsDtoMock);
    }
}
