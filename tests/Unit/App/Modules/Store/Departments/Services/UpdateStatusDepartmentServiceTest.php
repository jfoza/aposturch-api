<?php

namespace Tests\Unit\App\Modules\Store\Departments\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Departments\Repositories\DepartmentsRepository;
use App\Modules\Store\Departments\Services\UpdateStatusDepartmentsService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateStatusDepartmentServiceTest extends TestCase
{
    private  MockObject|DepartmentsRepositoryInterface $departmentsRepositoryMock;

    private string $department1;
    private string $department2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->departmentsRepositoryMock = $this->createMock(DepartmentsRepository::class);

        $this->department1 = Uuid::uuid4Generate();
        $this->department2 = Uuid::uuid4Generate();
    }

    public function getUpdateStatusDepartmentService(): UpdateStatusDepartmentsService
    {
        return new UpdateStatusDepartmentsService(
            $this->departmentsRepositoryMock
        );
    }

    public function test_should_update_status_of_many_departments_id()
    {
        $UpdateStatusDepartmentService = $this->getUpdateStatusDepartmentService();

        $UpdateStatusDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_STATUS_UPDATE->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([
                        Department::ID     => $this->department1,
                        Department::ACTIVE => false,
                    ]),
                    (object) ([
                        Department::ID     => $this->department2,
                        Department::ACTIVE => true,
                    ])
                ])
            );

        $updated = $UpdateStatusDepartmentService->execute([$this->department1, $this->department2]);

        $this->assertInstanceOf(Collection::class, $updated);
    }

    public function test_should_return_exception_if_any_of_the_departments_are_not_found()
    {
        $UpdateStatusDepartmentService = $this->getUpdateStatusDepartmentService();

        $UpdateStatusDepartmentService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_STATUS_UPDATE->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findAllByIds')
            ->willReturn(
                Collection::make([
                    (object) ([
                        Department::ID     => $this->department1,
                        Department::ACTIVE => false,
                    ]),
                    (object) ([
                        Department::ID     => Uuid::uuid4Generate(),
                        Department::ACTIVE => true,
                    ])
                ])
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::DEPARTMENT_NOT_FOUND));

        $UpdateStatusDepartmentService->execute([$this->department1, $this->department2]);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $UpdateStatusDepartmentService = $this->getUpdateStatusDepartmentService();

        $UpdateStatusDepartmentService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $UpdateStatusDepartmentService->execute([$this->department1, $this->department2]);
    }
}
