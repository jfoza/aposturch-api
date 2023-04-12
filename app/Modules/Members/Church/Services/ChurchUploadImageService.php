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
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Facades\Storage;

class ChurchUploadImageService extends Service implements ChurchUploadImageServiceInterface
{
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
            $this->removeImageIfAlreadyExists($church);

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

    private function removeImageIfAlreadyExists(object $church): void
    {
        if($images = $church->imagesChurch) {
            $this->churchRepository->saveImages($church->id, []);

            foreach ($images as $image)
            {
                $this->imagesRepository->remove($image->id);

                Storage::delete($image->path);
            }
        }
    }
}
