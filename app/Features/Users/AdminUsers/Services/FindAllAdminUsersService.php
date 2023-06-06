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
            $policy->haveRule(RulesEnum::ADMIN_USERS_SUPPORT_VIEW->value)      => $this->findAllBySupport(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->findAllByAdminMaster(),

            default  => $policy->dispatchForbiddenError(),
        };
    }

    private function findAllBySupport(): LengthAwarePaginator|Collection
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findAllByAdminMaster(): LengthAwarePaginator|Collection
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }
}
