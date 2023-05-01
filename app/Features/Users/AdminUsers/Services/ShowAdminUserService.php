<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Symfony\Component\HttpFoundation\Response;

class ShowAdminUserService extends Service implements ShowAdminUserServiceInterface
{
    private string $userId;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $userId): object
    {
        $this->userId = $userId;

        $policy = $this->getPolicy();

        $adminUser = match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_SUPPORT_VIEW->value)      => $this->showBySupport(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->showByAdminMaster(),

            default  => $policy->dispatchErrorForbidden(),
        };

        if(empty($adminUser)) {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $adminUser;
    }

    private function showBySupport(): ?object
    {
        $profiles = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ];

        return $this->adminUsersRepository->findById($this->userId, $profiles);
    }

    private function showByAdminMaster(): ?object
    {
        $profiles = [
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ];

        return $this->adminUsersRepository->findById($this->userId, $profiles);
    }
}
