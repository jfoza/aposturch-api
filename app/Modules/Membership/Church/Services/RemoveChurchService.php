<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\RemoveChurchServiceInterface;
use App\Modules\Membership\Church\Traits\ChurchOperationsTrait;
use App\Modules\Membership\Church\Traits\RemoveChurchValidationsTrait;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveChurchService extends AuthenticatedService implements RemoveChurchServiceInterface
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
        $this->getPolicy()->havePermission(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE->value);

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

            EnvironmentException::dispatchException($e);
        }
    }
}
