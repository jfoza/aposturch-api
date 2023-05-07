<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Membership\Members\Contracts\FindAllMembersResponsibleServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Shared\Enums\RulesEnum;

class FindAllMembersResponsibleService extends Service implements FindAllMembersResponsibleServiceInterface
{
    public function __construct(
        private readonly MembersRepositoryInterface $membersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute()
    {
        $insert = $this->getPolicy()->haveRule(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value);
        $update = $this->getPolicy()->haveRule(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_UPDATE->value);

        if($insert || $update)
        {
            return $this->membersRepository->findAllResponsible();
        }

        return $this->getPolicy()->dispatchErrorForbidden();
    }
}
