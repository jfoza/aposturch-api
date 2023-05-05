<?php

namespace App\Modules\Membership\Church\Traits;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Models\Member;
use App\Shared\Enums\MemberTypesEnum;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

trait ResponsibleMemberValidationsTrait
{
    private array $responsibleMembers;

    /**
     * @throws AppException
     */
    public function isValidMembersResponsible(
        array $responsibleMembers,
        MembersRepositoryInterface $membersRepository
    ): void
    {
        $this->responsibleMembers = $responsibleMembers;

        $this->validateResponsibleMembersQuantity();

        $members = $this->getMembersOrFail($membersRepository);

        $this->validateProfileAndMemberType($members->toArray());
    }

    /**
     * @throws AppException
     */
    private function validateResponsibleMembersQuantity(): void
    {
        if(count($this->responsibleMembers) > 3)
        {
            throw new AppException(
                MessagesEnum::INVALID_RESP_MEMBERS_QUANTITY,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    private function getMembersOrFail(MembersRepositoryInterface $membersRepository): mixed
    {
        $members = $membersRepository->findByIds($this->responsibleMembers);

        $ids = $members->pluck(Member::ID)->toArray();

        $notFound = [];

        foreach ($this->responsibleMembers as $responsibleMember)
        {
            if(!in_array($responsibleMember, $ids))
            {
                $notFound[] = $responsibleMember;
            }
        }

        if(!empty($notFound))
        {
            throw new AppException(
                MessagesEnum::MEMBER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $members;
    }

    /**
     * @throws AppException
     */
    private function validateProfileAndMemberType(array $members): void
    {
        foreach ($members as $member)
        {
            if($member['member_type']['unique_name'] != MemberTypesEnum::RESPONSIBLE->value)
            {
                throw new AppException(
                    MessagesEnum::INVALID_RESP_MEMBER_TYPE,
                    Response::HTTP_BAD_REQUEST
                );
            }

            if(!$profilesUser = collect($member['user']['profile']))
            {
                throw new AppException(
                    MessagesEnum::USER_NOT_FOUND,
                    Response::HTTP_NOT_FOUND
                );
            }

            if(!$profilesUser->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_CHURCH->value)->first())
            {
                throw new AppException(
                    MessagesEnum::INVALID_RESP_MEMBER_PROFILE,
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
    }
}
