<?php

namespace Tests\Unit\App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\Repositories\ImagesRepository;
use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Features\Users\Users\Services\RemoveUserAvatarService;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Features\Users\Users\UsersDataProvidersTrait;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\UsersLists;

class RemoveUserAvatarServiceTest extends TestCase
{
    use UsersDataProvidersTrait;

    private string $userId;
    private string $churchId;
    private string $moduleId;
    private mixed $churches;
    private mixed $modules;

    protected MockObject|MembersRepositoryInterface $membersRepositoryMock;
    protected MockObject|UsersRepositoryInterface   $usersRepositoryMock;
    protected MockObject|ImagesRepositoryInterface  $imagesRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);
        $this->usersRepositoryMock   = $this->createMock(UsersRepository::class);
        $this->imagesRepositoryMock  = $this->createMock(ImagesRepository::class);

        $this->userId = Uuid::uuid4Generate();
        $this->churchId = Uuid::uuid4Generate();
        $this->moduleId = Uuid::uuid4Generate();

        $this->churches = Collection::make([(object) ([Church::ID => $this->churchId])]);
        $this->modules  = Collection::make([(object) ([Module::ID => $this->moduleId])]);
    }

    public function getRemoveUserAvatarService(): RemoveUserAvatarService
    {
        return new RemoveUserAvatarService(
            $this->membersRepositoryMock,
            $this->usersRepositoryMock,
            $this->imagesRepositoryMock,
        );
    }

    public function test_should_remove_image_user_avatar_by_admin_master(): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MASTER->value])
        );

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser());

        $removeUserAvatarService->execute(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    /**
     * @dataProvider dataProviderUploadImage
     *
     * @param string $rule
     * @param string $profileUniqueName
     * @return void
     * @throws AppException
     */
    public function test_should_insert_new_user_member_image_by_members(
        string $rule,
        string $profileUniqueName
    ): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([$rule])
        );

        $removeUserAvatarService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    $this->modules,
                    $profileUniqueName,
                )
            );

        $removeUserAvatarService->execute(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_user_not_exists_by_admin_master(): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MASTER->value])
        );

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $removeUserAvatarService->execute(Uuid::uuid4Generate());
    }

    /**
     * @dataProvider dataProviderUploadImage
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_exception_if_user_member_not_exists_by_members(
        string $rule
    ): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([$rule])
        );

        $removeUserAvatarService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $removeUserAvatarService->execute(Uuid::uuid4Generate());
    }

    /**
     * @dataProvider dataProviderUploadImageModulesValidation
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_authenticated_user_does_not_have_access_to_module(
        string $rule,
    ): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([$rule])
        );

        $removeUserAvatarService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                Uuid::uuid4Generate(),
            )
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    Collection::make([(object) ([Module::ID => Uuid::uuid4Generate()])]),
                    ProfileUniqueNameEnum::ASSISTANT->value,
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::MODULE_NOT_ALLOWED));

        $removeUserAvatarService->execute(Uuid::uuid4Generate());
    }

    /**
     * @dataProvider dataProviderUploadImageModulesValidation
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_authenticated_user_does_not_have_access_to_church(
        string $rule,
    ): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([$rule])
        );

        $removeUserAvatarService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                Uuid::uuid4Generate(),
                $this->moduleId
            )
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => Uuid::uuid4Generate()])]),
                    $this->modules,
                    ProfileUniqueNameEnum::ASSISTANT->value,
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::NO_ACCESS_TO_CHURCH_MEMBERS));

        $removeUserAvatarService->execute(Uuid::uuid4Generate());
    }

    /**
     * @dataProvider dataProviderUploadImageModulesValidation
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_user_tries_to_update_a_superior_profile_in_members(
        string $rule,
    ): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([$rule])
        );

        $removeUserAvatarService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    $this->modules,
                    RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_CHURCH->value,
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $removeUserAvatarService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_no_has_image_by_admin_master(): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MASTER->value])
        );

        $user = UsersLists::showUser();

        $user->image = null;

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn($user);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_WITHOUT_IMAGE));

        $removeUserAvatarService->execute(Uuid::uuid4Generate());
    }

    /**
     * @dataProvider dataProviderUploadImageModulesValidation
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_user_no_has_image_in_members(
        string $rule,
    ): void
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([$rule])
        );

        $removeUserAvatarService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $userMember = MemberLists::getMemberDataView(
            $this->churches,
            $this->modules,
            ProfileUniqueNameEnum::MEMBER->value,
        );

        $userMember->user->image = null;

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn($userMember);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_WITHOUT_IMAGE));

        $removeUserAvatarService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeUserAvatarService = $this->getRemoveUserAvatarService();

        $removeUserAvatarService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeUserAvatarService->execute(Uuid::uuid4Generate());
    }
}
