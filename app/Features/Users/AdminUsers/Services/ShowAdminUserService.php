<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Validations\ProfileHierarchyValidation;
use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\Profiles\Models\Profile;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Symfony\Component\HttpFoundation\Response;

class ShowAdminUserService extends AuthenticatedService implements ShowAdminUserServiceInterface
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

        return match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_SUPPORT_VIEW->value)      => $this->showBySupport(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->showByAdminMaster(),

            default  => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function showBySupport(): object
    {
        $adminUser = $this->findOrFail();

        $profilesUniqueName = $adminUser->profile->pluck(Profile::UNIQUE_NAME)->toArray();

        ProfileHierarchyValidation::technicalSupportInListingsValidation($profilesUniqueName);

        return $adminUser;
    }

    /**
     * @throws AppException
     */
    private function showByAdminMaster(): object
    {
        $adminUser = $this->findOrFail();

        $profilesUniqueName = $adminUser->profile->pluck(Profile::UNIQUE_NAME)->toArray();

        ProfileHierarchyValidation::adminMasterInListingsValidation($profilesUniqueName);

        return $adminUser;
    }

    /**
     * @throws AppException
     */
    private function findOrFail(): object
    {
        if(!$adminUser = $this->adminUsersRepository->findByUserId($this->userId))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $adminUser;
    }
}
