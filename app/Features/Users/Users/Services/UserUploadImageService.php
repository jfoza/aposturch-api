<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Contracts\UserUploadImageServiceInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Services\MembersBaseService;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UserUploadImageService extends MembersBaseService implements UserUploadImageServiceInterface
{
    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        protected readonly UsersRepositoryInterface $usersRepository,
        protected readonly ImagesRepositoryInterface $imagesRepository,
    )
    {
        parent::__construct($this->membersRepository);
    }

    private ImagesDTO $imagesDTO;
    private string $userId;

    /**
     * @throws AppException
     */
    public function execute(ImagesDTO $imagesDTO, string $userId): object
    {
        $this->imagesDTO = $imagesDTO;
        $this->userId = $userId;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MASTER->value)    => $this->uploadByAdminMaster(),
            $policy->haveRule(RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_CHURCH->value)    => $this->uploadByAdminChurch(),
            $policy->haveRule(RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MODULE->value)    => $this->uploadByAdminModule(),
            $policy->haveRule(RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT->value) => $this->uploadByAssistant(),

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function uploadByAdminMaster(): ?object
    {
        UsersValidations::validateUserExistsById(
            $this->userId,
            $this->usersRepository
        );

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     */
    private function uploadByAdminChurch(): ?object
    {
        $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value
        );

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     */
    private function uploadByAdminModule(): ?object
    {
        $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_MODULE->value
        );

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     */
    private function uploadByAssistant(): ?object
    {
        $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ASSISTANT->value
        );

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     */
    private function baseUploadOperation(): ?object
    {
        Transaction::beginTransaction();

        try
        {
            $this->imagesDTO->type = TypeUploadImageEnum::USER_AVATAR->value;
            $this->imagesDTO->path = $this->imagesDTO->image->store(TypeUploadImageEnum::USER_AVATAR->value);

            $imageData = $this->imagesRepository->create($this->imagesDTO);

            $this->usersRepository->saveAvatar($this->userId, $imageData->id);

            Transaction::commit();

            return $imageData;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
