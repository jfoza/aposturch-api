<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\Updates\PasswordDataUpdateService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\MemberLists;

class PasswordDataUpdateServiceTest extends TestCase
{
    use MembersProvidersTrait;

    protected MockObject|MembersRepositoryInterface $membersRepositoryMock;
    protected MockObject|UsersRepositoryInterface   $usersRepositoryMock;

    protected string $churchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock    = $this->createMock(MembersRepository::class);
        $this->usersRepositoryMock      = $this->createMock(UsersRepository::class);
        $this->updateMemberResponseMock = $this->createMock(UpdateMemberResponse::class);

        $this->churchId  = Uuid::uuid4Generate();
    }

    public function getPasswordDataUpdateService(): PasswordDataUpdateService
    {
        return new PasswordDataUpdateService(
            $this->membersRepositoryMock,
            $this->usersRepositoryMock,
            $this->updateMemberResponseMock,
        );
    }

    /**
     * @dataProvider dataProviderUpdate
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_update_user_member_password_data(
        string $rule
    ): void
    {
        $passwordDataUpdateService = $this->getPasswordDataUpdateService();

        $passwordDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $passwordDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::MEMBER->value
                )
            );

        $updated = $passwordDataUpdateService->execute(Uuid::uuid4Generate(), 'password');

        $this->assertInstanceOf(UpdateMemberResponse::class, $updated);
    }

    /**
     * @dataProvider dataProviderUpdate
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_user_not_exists(
        string $rule
    ): void
    {
        $passwordDataUpdateService = $this->getPasswordDataUpdateService();

        $passwordDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $passwordDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $passwordDataUpdateService->execute(Uuid::uuid4Generate(), 'password');
    }

    /**
     * @dataProvider dataProviderUpdateMemberValidationProfileAndModules
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_user_tries_to_update_a_superior_profile_in_members(
        string $rule,
    ): void
    {
        $passwordDataUpdateService = $this->getPasswordDataUpdateService();

        $passwordDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $passwordDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::ADMIN_CHURCH->value
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $passwordDataUpdateService->execute(Uuid::uuid4Generate(), 'password');
    }

    /**
     * @dataProvider dataProviderUpdateMemberValidationChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_authenticated_user_is_not_linked_to_the_churches(
        string $rule,
    ): void
    {
        $passwordDataUpdateService = $this->getPasswordDataUpdateService();

        $passwordDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $passwordDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(Uuid::uuid4Generate())
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => Uuid::uuid4Generate()])]),
                    ProfileUniqueNameEnum::MEMBER->value
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::NO_ACCESS_TO_CHURCH_MEMBERS));

        $passwordDataUpdateService->execute(Uuid::uuid4Generate(), 'password');
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $passwordDataUpdateService = $this->getPasswordDataUpdateService();

        $passwordDataUpdateService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $passwordDataUpdateService->execute(Uuid::uuid4Generate(), 'password');
    }
}