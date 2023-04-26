<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllByProfileUniqueNameServiceInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllByProfileUniqueNameService extends Service implements FindAllByProfileUniqueNameServiceInterface
{
    private AdminUsersFiltersDTO $adminUsersFiltersDTO;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(AdminUsersFiltersDTO $adminUsersFiltersDTO): LengthAwarePaginator|Collection
    {
        $policy = $this->getPolicy();

        $this->adminUsersFiltersDTO = $adminUsersFiltersDTO;

        return match (true)
        {
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_CHURCH_VIEW->value) => $this->findByAdminChurch(),

            default  => $policy->dispatchErrorForbidden(),
        };
    }

    private function findByAdminMaster(): LengthAwarePaginator|Collection
    {
        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findByAdminChurch(): LengthAwarePaginator|Collection
    {
        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }
}
