<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\PasswordDataUpdateServiceInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Auth;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class PasswordDataUpdateService extends AuthenticatedService implements PasswordDataUpdateServiceInterface
{

    private string $userId;
    private string $password;

    public function __construct(
        private readonly UsersRepositoryInterface   $usersRepository,
        private readonly MembersRepositoryInterface $membersRepository,
        private readonly MembersFiltersDTO          $membersFiltersDTO,
        private readonly UpdateMemberResponse       $updateMemberResponse,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $userId, string $password): UpdateMemberResponse
    {
        $this->userId = $userId;
        $this->password = $password;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value)
                => $this->updateByAdminMaster(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value)
                => $this->updateByAdminChurch(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): UpdateMemberResponse
    {
        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        if(!$this->userPayloadIsEqualsAuthUser($this->userId))
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $this->membersFiltersDTO->churchesId = $this->getUserMemberChurchesId();

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function handleGeneralValidations()
    {
        if(!$this->membersRepository->findOneByFilters($this->userId, $this->membersFiltersDTO))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @return UpdateMemberResponse
     * @throws AppException
     */
    public function updateMemberData(): UpdateMemberResponse
    {
        Transaction::beginTransaction();

        try
        {
            $encryptedPassword = Hash::generateHash($this->password);

            $this->usersRepository->saveNewPassword($this->userId, $encryptedPassword);

            $this->updateMemberResponse->id = $this->userId;

            if($this->userPayloadIsEqualsAuthUser($this->userId))
            {
                Auth::logout();
            }

            Transaction::commit();

            return $this->updateMemberResponse;
        }
        catch (Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
