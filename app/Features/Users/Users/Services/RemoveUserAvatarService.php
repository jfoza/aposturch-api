<?php

namespace App\Features\Users\Users\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Traits\UploadImagesTrait;
use App\Exceptions\AppException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\RemoveUserAvatarServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Services\MembersBaseService;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveUserAvatarService extends MembersBaseService implements RemoveUserAvatarServiceInterface
{
    private mixed $user;
    private string $userId;

    use UploadImagesTrait;

    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        protected readonly UsersRepositoryInterface $usersRepository,
        protected readonly ImagesRepositoryInterface $imagesRepository,
    )
    {
        parent::__construct($this->membersRepository);
    }

    /**
     * @throws AppException
     */
    public function execute(string $userId): void
    {
        $this->userId = $userId;

        $policy = $this->getPolicy();

        match (true)
        {
            $policy->haveRule(RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MASTER->value)    => $this->removeByAdminMaster(),
            $policy->haveRule(RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_CHURCH->value)    => $this->removeByAdminChurch(),
            $policy->haveRule(RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MODULE->value)    => $this->removeByAdminModule(),
            $policy->haveRule(RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT->value) => $this->removeByAssistant(),

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function removeByAdminMaster(): void
    {
        $this->user = UsersValidations::validateUserExistsById(
            $this->userId,
            $this->usersRepository
        );

        $this->baseOperation();
    }

    /**
     * @throws AppException
     */
    private function removeByAdminChurch(): void
    {
        $member = $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value
        );

        $this->user = $member->user;

        $this->baseOperation();
    }

    /**
     * @throws AppException
     */
    private function removeByAdminModule(): void
    {
        $member = $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_MODULE->value
        );

        $this->user = $member->user;

        $this->baseOperation();
    }

    /**
     * @throws AppException
     */
    private function removeByAssistant(): void
    {
        $member = $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ASSISTANT->value
        );

        $this->user = $member->user;

        $this->baseOperation();
    }

    /**
     * @throws AppException
     */
    private function baseOperation(): void
    {
        UsersValidations::userHasImage($this->user);

        Transaction::beginTransaction();

        try
        {
            $this->removeUserMemberImageIfAlreadyExists(
                $this->user,
                $this->usersRepository,
                $this->imagesRepository
            );

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
