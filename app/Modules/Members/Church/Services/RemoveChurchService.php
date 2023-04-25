<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\RemoveChurchServiceInterface;
use App\Modules\Members\Church\Traits\ChurchOperationsTrait;
use App\Modules\Members\Church\Traits\RemoveChurchValidationsTrait;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveChurchService extends Service implements RemoveChurchServiceInterface
{
    use ChurchOperationsTrait;
    use RemoveChurchValidationsTrait;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly ImagesRepositoryInterface $imagesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $churchId)
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_DELETE->value);

        $church = $this->validateChurchCanBeDelete(
            $this->churchRepository,
            $churchId
        );

        Transaction::beginTransaction();

        try
        {
            $this->removeImageIfAlreadyExists(
                $church,
                $this->churchRepository,
                $this->imagesRepository
            );

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
