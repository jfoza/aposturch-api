<?php

namespace App\Modules\Store\Departments\Validations;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class DepartmentsValidations
{
    /**
     * @throws AppException
     */
    public static function departmentExists(
        string $id,
        DepartmentsRepositoryInterface $departmentsRepository
    ): object
    {
        if(!$department = $departmentsRepository->findById($id))
        {
            throw new AppException(
                MessagesEnum::DEPARTMENT_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $department;
    }

    /**
     * @throws AppException
     */
    public static function departmentExistsByName(
        string $name,
        DepartmentsRepositoryInterface $departmentsRepository
    ): void
    {
        if($departmentsRepository->findByName($name))
        {
            throw new AppException(
                MessagesEnum::DEPARTMENT_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function departmentExistsByNameInUpdate(
        string $id,
        string $name,
        DepartmentsRepositoryInterface $departmentsRepository
    ): void
    {
        $department = $departmentsRepository->findByName($name);

        if($department && $department->id != $id)
        {
            throw new AppException(
                MessagesEnum::DEPARTMENT_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function hasSubcategories(
        string $departmentId,
        SubcategoriesRepositoryInterface $subcategoriesRepository
    ): void
    {
        $subcategories = $subcategoriesRepository->findByDepartment($departmentId);

        if($subcategories->isNotEmpty())
        {
            throw new AppException(
                MessagesEnum::DEPARTMENT_HAS_SUBCATEGORIES,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function departmentsExists(
        array $departmentsIdPayload,
        DepartmentsRepositoryInterface $departmentsRepository
    ): Collection
    {
        $departments = $departmentsRepository->findAllByIds($departmentsIdPayload);

        $departmentsId = $departments->pluck(Department::ID)->toArray();

        $notFound = [];

        foreach ($departmentsIdPayload as $departmentIdPayload)
        {
            if(!in_array($departmentIdPayload, $departmentsId))
            {
                $notFound[] = $departmentIdPayload;
            }
        }

        if(!empty($notFound))
        {
            throw new AppException(
                MessagesEnum::DEPARTMENT_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $departments;
    }
}
