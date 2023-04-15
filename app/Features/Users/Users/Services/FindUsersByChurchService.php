<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\Users\Contracts\FindUsersByChurchServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindUsersByChurchService extends Service implements FindUsersByChurchServiceInterface
{
    private UserFiltersDTO $userFiltersDTO;

    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(UserFiltersDTO $userFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->userFiltersDTO = $userFiltersDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_CHURCH_VIEW->value) => $this->findByAdminChurch(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MODULE_VIEW->value) => $this->findByAdminModule(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ASSISTANT_VIEW->value)    => $this->findByAssistant(),

            default  => $policy->dispatchErrorForbidden(),
        };
    }

    private function findByAdminMaster()
    {

    }

    private function findByAdminChurch()
    {

    }

    private function findByAdminModule()
    {

    }

    private function findByAssistant()
    {

    }
}
