<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Membership\Church\Contracts\RemoveResponsibleChurchRelationshipServiceInterface;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Modules\Membership\ResponsibleChurch\Contracts\ResponsibleChurchRepositoryInterface;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveResponsibleChurchRelationshipService extends Service implements RemoveResponsibleChurchRelationshipServiceInterface
{
    public function __construct(
        private readonly ResponsibleChurchRepositoryInterface $responsibleChurchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $adminUserId, string $churchId)
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_USER_RELATIONSHIP_DELETE->value);

        ChurchValidations::responsibleRelationshipExists(
            $adminUserId,
            $churchId,
            $this->responsibleChurchRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->responsibleChurchRepository->remove(
                $adminUserId,
                $churchId
            );

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
