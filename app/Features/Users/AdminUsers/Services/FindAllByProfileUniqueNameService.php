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
    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(AdminUsersFiltersDTO $adminUsersFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value);

        return $this->adminUsersRepository->findAll($adminUsersFiltersDTO);
    }
}
