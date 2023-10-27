<?php

namespace Tests\Unit\App\Modules\Store\Departments\Services;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\DTO\DepartmentsFiltersDTO;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Departments\Repositories\DepartmentsRepository;
use App\Modules\Store\Departments\Services\FindAllDepartmentsService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindAllDepartmentsServiceTest extends TestCase
{
    private  MockObject|DepartmentsRepositoryInterface $departmentsRepositoryMock;
    private  MockObject|DepartmentsFiltersDTO $departmentsFiltersDtoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->departmentsRepositoryMock = $this->createMock(DepartmentsRepository::class);
        $this->departmentsFiltersDtoMock = $this->createMock(DepartmentsFiltersDTO::class);
    }

    public function getFindAllDepartmentsService(): FindAllDepartmentsService
    {
        return new FindAllDepartmentsService(
            $this->departmentsRepositoryMock
        );
    }

    public function getDepartments(): Collection
    {
        return Collection::make([
            [
                Department::ID => Uuid::uuid4Generate(),
                Department::NAME => 'test',
                Department::DESCRIPTION => 'test',
            ]
        ]);
    }

    public function test_should_return_empty()
    {
        $findAllDepartmentsService = $this->getFindAllDepartmentsService();

        $findAllDepartmentsService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_VIEW->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findAll')
            ->willReturn(Collection::empty());

        $departments = $findAllDepartmentsService->execute($this->departmentsFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $departments);
    }

    public function test_should_return_departments_list()
    {
        $findAllDepartmentsService = $this->getFindAllDepartmentsService();

        $findAllDepartmentsService->setPolicy(
            new Policy([RulesEnum::STORE_MODULE_DEPARTMENTS_VIEW->value])
        );

        $this
            ->departmentsRepositoryMock
            ->method('findAll')
            ->willReturn($this->getDepartments());

        $departments = $findAllDepartmentsService->execute($this->departmentsFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $departments);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllDepartmentsService = $this->getFindAllDepartmentsService();

        $findAllDepartmentsService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllDepartmentsService->execute($this->departmentsFiltersDtoMock);
    }
}
