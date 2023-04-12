<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Infra\Models\Image;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\ChurchUploadImageServiceInterface;
use App\Modules\Members\Church\Traits\ChurchOperationsTrait;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class ChurchUploadImageService extends Service implements ChurchUploadImageServiceInterface
{
    use ChurchOperationsTrait;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly ImagesRepositoryInterface $imagesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ImagesDTO $imagesDTO, string $churchId): Image
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_IMAGE_UPLOAD->value);

        $church = ChurchValidations::churchIdExists(
            $this->churchRepository,
            $churchId,
        );

        Transaction::beginTransaction();

        try
        {
            $this->removeImageIfAlreadyExists(
                $church,
                $this->churchRepository,
                $this->imagesRepository
            );

            $imagesDTO->type = TypeUploadImageEnum::PRODUCT->value;
            $imagesDTO->path = $imagesDTO->image->store(TypeUploadImageEnum::CHURCH->value);

            $imageData = $this->imagesRepository->create($imagesDTO);

            $this->churchRepository->saveImages($churchId, [$imageData->id]);

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
