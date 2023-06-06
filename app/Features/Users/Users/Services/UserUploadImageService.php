<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\Users\AdminUsers\Validations\AllowedProfilesValidations;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Contracts\UserUploadImageServiceInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Auth;
use App\Shared\Utils\Transaction;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class UserUploadImageService extends Service implements UserUploadImageServiceInterface
{
    private ImagesDTO $imagesDTO;
    private string $userId;
    private object $userMember;

    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly MembersRepositoryInterface $membersRepository,
        private readonly ImagesRepositoryInterface $imagesRepository,
        private readonly MembersFiltersDTO $membersFiltersDTO,
    ) {}

    /**
     * @throws AppException
     * @throws UserNotDefinedException
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
     * @throws UserNotDefinedException
     */
    private function uploadByAdminChurch(): ?object
    {
        $this->userMemberExistsAndCanBeUsed();

        if($this->userId != Auth::getId())
        {
            AllowedProfilesValidations::validateAdminChurchProfile($this->userMember->profile_unique_name);
        }

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    private function uploadByAdminModule(): ?object
    {
        $this->userMemberExistsAndCanBeUsed();

        if($this->userId != Auth::getId())
        {
            AllowedProfilesValidations::validateAdminModuleProfile($this->userMember->profile_unique_name);
        }

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    private function uploadByAssistant(): ?object
    {
        $this->userMemberExistsAndCanBeUsed();

        if($this->userId != Auth::getId())
        {
            AllowedProfilesValidations::validateAssistantProfile($this->userMember->profile_unique_name);
        }

        return $this->baseUploadOperation();
    }

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    private function userMemberExistsAndCanBeUsed()
    {
        $this->membersFiltersDTO->churchIds = $this->getChurchesUserMember()->pluck(Church::ID)->toArray();

        $this->userMember = MembersValidations::memberExists(
            $this->userId,
            $this->membersFiltersDTO,
            $this->membersRepository
        );
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
