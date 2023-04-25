<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Members\Church\Contracts\RemoveResponsibleChurchRelationshipServiceInterface;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Modules\Members\ResponsibleChurch\Contracts\ResponsibleChurchRepositoryInterface;
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
