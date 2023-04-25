<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllAdminUsersServiceInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllAdminUsersService extends Service implements FindAllAdminUsersServiceInterface
{
    private AdminUsersFiltersDTO $adminUsersFiltersDTO;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(AdminUsersFiltersDTO $adminUsersFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->adminUsersFiltersDTO = $adminUsersFiltersDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_CHURCH_VIEW->value) => $this->findByAdminChurch(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MODULE_VIEW->value) => $this->findByAdminModule(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ASSISTANT_VIEW->value)    => $this->findByAssistant(),

            default  => $policy->dispatchErrorForbidden(),
        };
    }

    private function findByAdminMaster(): LengthAwarePaginator|Collection
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findByAdminChurch(): LengthAwarePaginator|Collection
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findByAdminModule(): LengthAwarePaginator|Collection
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findByAssistant(): LengthAwarePaginator|Collection
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ASSISTANT->value,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }
}
