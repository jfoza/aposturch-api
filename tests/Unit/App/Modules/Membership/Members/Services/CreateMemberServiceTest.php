<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Repositories\CityRepository;
use App\Features\Module\Modules\Contracts\ModulesRepositoryInterface;
use App\Features\Module\Modules\Repositories\ModulesRepository;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Persons\DTO\PersonDTO;
use App\Features\Persons\Infra\Repositories\PersonsRepository;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\PasswordDTO;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MemberDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\InsertMemberResponse;
use App\Modules\Membership\Members\Services\CreateMemberService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\CitiesLists;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\ModulesLists;
use Tests\Unit\App\Resources\ProfilesLists;
use Tests\Unit\App\Resources\UsersLists;

class CreateMemberServiceTest extends TestCase
{
    use MembersProvidersTrait;

    private MockObject|PersonsRepositoryInterface  $personsRepositoryMock;
    private MockObject|UsersRepositoryInterface    $usersRepositoryMock;
    private MockObject|MembersRepositoryInterface  $membersRepositoryMock;
    private MockObject|ChurchRepositoryInterface   $churchRepositoryMock;
    private MockObject|ProfilesRepositoryInterface $profilesRepositoryMock;
    private MockObject|CityRepositoryInterface $cityRepositoryMock;
    private MockObject|ModulesRepositoryInterface $modulesRepositoryMock;
    private MockObject|UserDTO $userDtoMock;

    private string $profileId;
    private string $cityId;
    private string $churchId;
    private string $moduleId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profileId = Uuid::uuid4Generate();
        $this->cityId    = Uuid::uuid4Generate();
        $this->churchId  = Uuid::uuid4Generate();
        $this->moduleId  = Uuid::uuid4Generate();

        $this->createUserDtoMock();
        $this->createMocks();
    }

    public function createUserDtoMock(): void
    {
        $this->userDtoMock = $this->createMock(UserDTO::class);

        $this->userDtoMock->person                = $this->createMock(PersonDTO::class);
        $this->userDtoMock->member                = $this->createMock(MemberDTO::class);
        $this->userDtoMock->emailVerificationDTO  = $this->createMock(EmailVerificationDTO::class);
        $this->userDtoMock->passwordDTO           = $this->createMock(PasswordDTO::class);

        $this->userDtoMock->passwordDTO->password = 'test';

        $this->userDtoMock->name = 'test';
        $this->userDtoMock->email = 'test@test.com';
        $this->userDtoMock->profileId = $this->profileId;
        $this->userDtoMock->active = true;
        $this->userDtoMock->modulesId = [
            $this->moduleId
        ];

        $this->userDtoMock->person->phone = '51998765432';
        $this->userDtoMock->person->zipCode = '99999999';
        $this->userDtoMock->person->address = 'test';
        $this->userDtoMock->person->numberAddress = '65';
        $this->userDtoMock->person->complement = 'test';
        $this->userDtoMock->person->district = 'test';
        $this->userDtoMock->person->cityId = $this->cityId;
        $this->userDtoMock->person->uf = 'RS';

        $this->userDtoMock->member->churchId = $this->churchId;
    }

    public function createMocks(): void
    {
        $this->personsRepositoryMock   = $this->createMock(PersonsRepository::class);
        $this->usersRepositoryMock     = $this->createMock(UsersRepository::class);
        $this->membersRepositoryMock   = $this->createMock(MembersRepository::class);
        $this->churchRepositoryMock    = $this->createMock(ChurchRepository::class);
        $this->profilesRepositoryMock  = $this->createMock(ProfilesRepository::class);
        $this->cityRepositoryMock      = $this->createMock(CityRepository::class);
        $this->modulesRepositoryMock   = $this->createMock(ModulesRepository::class);
    }

    public function getCreateMemberService(): CreateMemberService
    {
        $createMembersService = new CreateMemberService(
            $this->personsRepositoryMock,
            $this->usersRepositoryMock,
            $this->membersRepositoryMock,
            $this->churchRepositoryMock,
            $this->profilesRepositoryMock,
            $this->cityRepositoryMock,
            $this->modulesRepositoryMock,
        );

        $createMembersService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        return $createMembersService;
    }

    /**
     * @dataProvider dataProviderInsertNewMember
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_insert_new_member(
        string $rule
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAssistantProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(null);

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById($this->cityId));

        $this
            ->personsRepositoryMock
            ->method('create')
            ->willReturn(UsersLists::getPersonCreated());

        $this
            ->usersRepositoryMock
            ->method('create')
            ->willReturn(UsersLists::showUser());

        $this
            ->membersRepositoryMock
            ->method('create')
            ->willReturn(MemberLists::memberCreated());

        $created = $createMembersService->execute($this->userDtoMock);

        $this->assertInstanceOf(InsertMemberResponse::class, $created);
    }

    /**
     * @dataProvider dataProviderInsertNewMember
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_profile_id_not_exists(
        string $rule
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $createMembersService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderInsertNewMember
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_email_already_exists(
        string $rule
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminChurchProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $createMembersService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderInsertNewMember
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_phone_already_exists(
        string $rule
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminChurchProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(UsersLists::showUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $createMembersService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderInsertNewMember
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_church_id_not_exists(
        string $rule
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminChurchProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(null);

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $createMembersService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderInsertNewMember
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_module_id_not_exists(
        string $rule
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminChurchProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers(
                Uuid::uuid4Generate()
            ));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $createMembersService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderInsertNewMember
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_city_id_not_exists(
        string $rule
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminChurchProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(null);

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $createMembersService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderInsertNewMemberProfilesModulesValidation
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_authenticated_user_does_not_have_access_to_module(
        string $rule,
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                Uuid::uuid4Generate(),
            )
        );

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAssistantProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(null);

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById($this->cityId));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::MODULE_NOT_ALLOWED));

        $createMembersService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderInsertNewMemberChurchValidation
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_authenticated_user_is_not_linked_to_the_churches(
        string $rule
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAssistantProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(null);

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById($this->cityId));

        $this->userDtoMock->member->churchId = Uuid::uuid4Generate();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createMembersService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderInsertNewMemberProfilesModulesValidation
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_user_tries_to_update_a_superior_profile_in_members(
        string $rule,
    ): void
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findById')
            ->willReturn(ProfilesLists::getAdminChurchProfile($this->profileId));

        $this
            ->modulesRepositoryMock
            ->method('findByModulesIdInCreateMembers')
            ->willReturn(ModulesLists::getModulesByIdInCreateMembers($this->moduleId));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(null);

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById($this->cityId));

        $this
            ->personsRepositoryMock
            ->method('create')
            ->willReturn(UsersLists::getPersonCreated());

        $this
            ->usersRepositoryMock
            ->method('create')
            ->willReturn(UsersLists::showUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $createMembersService->execute($this->userDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createMembersService = $this->getCreateMemberService();

        $createMembersService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createMembersService->execute($this->userDtoMock);
    }
}
