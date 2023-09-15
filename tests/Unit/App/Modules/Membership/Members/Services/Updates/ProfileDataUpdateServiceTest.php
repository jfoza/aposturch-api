<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\Updates\ProfileDataUpdateService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\ProfilesLists;

class ProfileDataUpdateServiceTest extends TestCase
{
    use MembersProvidersTrait;

    protected MockObject|MembersRepositoryInterface  $membersRepositoryMock;
    protected MockObject|UsersRepositoryInterface    $usersRepositoryMock;
    protected MockObject|ProfilesRepositoryInterface $profilesRepositoryMock;

    protected string $churchId;
    protected string $profileId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock    = $this->createMock(MembersRepository::class);
        $this->usersRepositoryMock      = $this->createMock(UsersRepository::class);
        $this->profilesRepositoryMock   = $this->createMock(ProfilesRepository::class);
        $this->updateMemberResponseMock = $this->createMock(UpdateMemberResponse::class);

        $this->churchId  = Uuid::uuid4Generate();
        $this->profileId = Uuid::uuid4Generate();
    }

    public function getProfileDataUpdateService(): ProfileDataUpdateService
    {
        return new ProfileDataUpdateService(
            $this->membersRepositoryMock,
            $this->usersRepositoryMock,
            $this->profilesRepositoryMock,
            $this->updateMemberResponseMock,
        );
    }

    /**
     * @dataProvider dataProviderUpdateMemberWithAdminMasterAndAdminChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_update_user_member_profile_data(
        string $rule
    ): void
    {
        $profileDataUpdateService = $this->getProfileDataUpdateService();

        $profileDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $profileDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAssistantProfile($this->profileId));

        $updated = $profileDataUpdateService->execute(
            Uuid::uuid4Generate(),
            $this->profileId
        );

        $this->assertInstanceOf(UpdateMemberResponse::class, $updated);
    }

    /**
     * @dataProvider dataProviderUpdateMemberWithAdminMasterAndAdminChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_profile_not_exists(
        string $rule
    ): void
    {
        $profileDataUpdateService = $this->getProfileDataUpdateService();

        $profileDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $profileDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_FOUND));

        $profileDataUpdateService->execute(
            Uuid::uuid4Generate(),
            $this->profileId
        );
    }

    /**
     * @dataProvider dataProviderUpdateMemberWithAdminMasterAndAdminChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_user_not_exists(
        string $rule
    ): void
    {
        $profileDataUpdateService = $this->getProfileDataUpdateService();

        $profileDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $profileDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $profileDataUpdateService->execute(
            Uuid::uuid4Generate(),
            $this->profileId
        );
    }

    public function test_should_return_exception_if_the_authenticated_user_is_not_linked_to_the_churches()
    {
        $profileDataUpdateService = $this->getProfileDataUpdateService();

        $profileDataUpdateService->setPolicy(
            new Policy([RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value])
        );

        $profileDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(Uuid::uuid4Generate())
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => Uuid::uuid4Generate()])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::NO_ACCESS_TO_CHURCH_MEMBERS));

        $profileDataUpdateService->execute(
            Uuid::uuid4Generate(),
            $this->profileId
        );
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $profileDataUpdateService = $this->getProfileDataUpdateService();

        $profileDataUpdateService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $profileDataUpdateService->execute(
            Uuid::uuid4Generate(),
            $this->profileId
        );
    }
}
