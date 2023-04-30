<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Infra\Models\Image;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\ChurchUploadImageServiceInterface;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Church\Traits\ChurchOperationsTrait;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class ChurchUploadImageService extends Service implements ChurchUploadImageServiceInterface
{
    use ChurchOperationsTrait;

    private ImagesDTO $imagesDTO;
    private string $churchId;
    private object $church;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly ImagesRepositoryInterface $imagesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ImagesDTO $imagesDTO, string $churchId): Image
    {
        $this->imagesDTO = $imagesDTO;
        $this->churchId = $churchId;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_IMAGE_UPLOAD->value) => $this->uploadByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_IMAGE_UPLOAD->value) => $this->uploadByAdminChurch(),

            default => $policy->dispatchErrorForbidden()
        };
    }

    /**
     * @throws AppException
     */
    private function uploadByAdminMaster(): ?Image
    {
        $this->handleValidations();

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     */
    private function uploadByAdminChurch(): ?Image
    {
        $this->handleValidations();

        $this->userHasChurch(
            Church::ID,
            $this->churchId
        );

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): ?object
    {
        $this->church = ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->churchId,
        );

        return $this->church;
    }

    /**
     * @throws AppException
     */
    private function baseUploadOperation(): ?Image
    {
        Transaction::beginTransaction();

        try
        {
            $this->removeImageIfAlreadyExists(
                $this->church,
                $this->churchRepository,
                $this->imagesRepository
            );

            $this->imagesDTO->type = TypeUploadImageEnum::PRODUCT->value;
            $this->imagesDTO->path = $this->imagesDTO->image->store(TypeUploadImageEnum::CHURCH->value);

            $imageData = $this->imagesRepository->create($this->imagesDTO);

            $this->churchRepository->saveImages($this->churchId, [$imageData->id]);

            Transaction::commit();

            return $imageData;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
