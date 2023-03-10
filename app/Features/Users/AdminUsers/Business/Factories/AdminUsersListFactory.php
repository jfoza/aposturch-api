<?php

namespace App\Features\Users\AdminUsers\Business\Factories;

use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Users\AdminUsers\Contracts\AdminUsersListFactoryInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class AdminUsersListFactory
    extends Business
    implements AdminUsersListFactoryInterface
{
    private mixed $adminUsers;
    private AdminUsersFiltersDTO $adminUsersFiltersDTO;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function findAllByProfileRule(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        $this->adminUsersFiltersDTO = $adminUsersFiltersDTO;

        $callback = function () {
            return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
        };

        return $this
            ->findByAdminMaster($callback)
            ->findByEmployee($callback)
            ->adminUsers
            ?? $this->getPolicy()->dispatchErrorForbidden();
    }

    /**
     * @throws AppException
     */
    public function showByProfileRule(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        $this->adminUsersFiltersDTO = $adminUsersFiltersDTO;

        $callback = function () {
            $adminUser = $this->adminUsersRepository->findByUserIdAndProfileUniqueName($this->adminUsersFiltersDTO);

            if(empty($adminUser)) {
                throw new AppException(
                    MessagesEnum::USER_NOT_FOUND,
                    Response::HTTP_NOT_FOUND
                );
            }

            return $adminUser;
        };

        return $this
                ->findByAdminMaster($callback)
                ->findByEmployee($callback)
                ->adminUsers
            ?? $this->getPolicy()->dispatchErrorForbidden();
    }

    private function findByAdminMaster(Closure $callback): static
    {
        if($this->getPolicy()->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value)) {
            $this->adminUsersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ADMIN_MASTER,
                ProfileUniqueNameEnum::EMPLOYEE,
            ];

            $this->adminUsers = $callback($this->adminUsersFiltersDTO);
        }

        return $this;
    }

    private function findByEmployee(Closure $callback): static
    {
        if($this->getPolicy()->haveRule(RulesEnum::ADMIN_USERS_EMPLOYEE_VIEW->value)) {
            $this->adminUsersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::EMPLOYEE,
            ];

            $this->adminUsers = $callback($this->adminUsersFiltersDTO);
        }

        return $this;
    }
}
