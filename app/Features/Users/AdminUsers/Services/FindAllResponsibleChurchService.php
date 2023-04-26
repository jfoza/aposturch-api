<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllResponsibleChurchServiceInterface;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;

class FindAllResponsibleChurchService extends Service implements FindAllResponsibleChurchServiceInterface
{
    private string $churchId;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $churchId): mixed
    {
        $this->churchId = $churchId;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_VIEW->value) => $this->findByAdminChurch(),

            default => $policy->dispatchErrorForbidden(),
        };
    }

    /**
     * @throws AppException
     */
    private function findByAdminMaster()
    {
        ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->churchId
        );

        return $this->adminUsersRepository->findAllResponsibleChurch($this->churchId);
    }

    /**
     * @throws AppException
     */
    private function findByAdminChurch()
    {
        ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->churchId
        );

        return $this->adminUsersRepository->findAllResponsibleChurch($this->churchId);
    }
}
