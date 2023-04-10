<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\RemoveChurchServiceInterface;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveChurchService extends Service implements RemoveChurchServiceInterface
{
    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $churchId)
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_DELETE->value);

        ChurchValidations::churchExistsAndHasMembers(
            $this->churchRepository,
            $churchId
        );

        Transaction::beginTransaction();

        try
        {
            $this->churchRepository->remove($churchId);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
