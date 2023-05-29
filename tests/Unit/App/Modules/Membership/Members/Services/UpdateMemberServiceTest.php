<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Repositories\CityRepository;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Persons\DTO\PersonDTO;
use App\Features\Persons\Infra\Repositories\PersonsRepository;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;
use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MemberDTO;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\UpdateMemberService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\Services\Providers\MembersProvidersTrait;
use Tests\Unit\App\Resources\CitiesLists;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\UsersLists;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateMemberServiceTest extends TestCase
{
    use MembersProvidersTrait;

    private MockObject|PersonsRepositoryInterface  $personsRepositoryMock;
    private MockObject|UsersRepositoryInterface    $usersRepositoryMock;
    private MockObject|MembersRepositoryInterface  $membersRepositoryMock;
    private MockObject|CityRepositoryInterface $cityRepositoryMock;
    private MockObject|UserDTO $userDtoMock;
    private MockObject|MembersFiltersDTO $membersFiltersDtoMock;

    private string $userId;
    private string $cityId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userId = Uuid::uuid4Generate();
        $this->cityId    = Uuid::uuid4Generate();
        $this->churchId  = Uuid::uuid4Generate();

        JWTAuth::shouldReceive('user')->andreturn(MemberLists::getMemberUserLogged($this->defaultChurchId));
        Auth::shouldReceive('user')->andreturn(MemberLists::getMemberUserLogged($this->defaultChurchId));

        JWTAuth::shouldReceive('id')->andreturn($this->userId);
        Auth::shouldReceive('id')->andreturn($this->userId);

        $this->createUserDtoMock();
        $this->createMocks();
    }

    public function createUserDtoMock()
    {
        $this->userDtoMock = $this->createMock(UserDTO::class);

        $this->userDtoMock->person                    = $this->createMock(PersonDTO::class);
        $this->userDtoMock->member                    = $this->createMock(MemberDTO::class);
        $this->userDtoMock->emailVerificationDTO      = $this->createMock(EmailVerificationDTO::class);
        $this->userDtoMock->newPasswordGenerationsDTO = $this->createMock(NewPasswordGenerationsDTO::class);

        $this->userDtoMock->id = $this->userId;
        $this->userDtoMock->name = 'test';
        $this->userDtoMock->email = 'email.example@email.com';

        $this->userDtoMock->person->phone = '51998765432';
        $this->userDtoMock->person->zipCode = '99999999';
        $this->userDtoMock->person->address = 'test';
        $this->userDtoMock->person->numberAddress = '65';
        $this->userDtoMock->person->complement = 'test';
        $this->userDtoMock->person->district = 'test';
        $this->userDtoMock->person->cityId = $this->cityId;
        $this->userDtoMock->person->uf = 'RS';
    }

    public function createMocks()
    {
        $this->personsRepositoryMock = $this->createMock(PersonsRepository::class);
        $this->usersRepositoryMock   = $this->createMock(UsersRepository::class);
        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);
        $this->cityRepositoryMock    = $this->createMock(CityRepository::class);
        $this->membersFiltersDtoMock = $this->createMock(MembersFiltersDTO::class);
    }

    public function getUpdateMemberService(): UpdateMemberService
    {
        return new UpdateMemberService(
            $this->personsRepositoryMock,
            $this->usersRepositoryMock,
            $this->membersRepositoryMock,
            $this->cityRepositoryMock,
            $this->membersFiltersDtoMock,
        );
    }

    /**
     * @dataProvider dataProviderUpdateUserMemberItself
     *
     * @param mixed $rule
     * @param mixed $churches
     * @return void
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function test_should_update_user_member_itself(
        mixed $rule,
        mixed $churches,
    ): void
    {
        $updateMemberService = $this->getUpdateMemberService();

        $updateMemberService->setPolicy(new Policy([$rule]));

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView($churches));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser($this->userId));

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(UsersLists::showUser($this->userId));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById($this->cityId));

        $this
            ->personsRepositoryMock
            ->method('save')
            ->willReturn(UsersLists::getPersonCreated());

        $this
            ->usersRepositoryMock
            ->method('saveInMembers')
            ->willReturn(UsersLists::showUser());

        $updated = $updateMemberService->execute($this->userDtoMock);

        $this->assertInstanceOf(UpdateMemberResponse::class, $updated);
    }

    /**
     * @dataProvider dataProviderUpdateUniqueUserMember
     *
     * @param mixed $rule
     * @param mixed $churches
     * @return void
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function test_should_update_unique_user_member(
        mixed $rule,
        mixed $churches,
    ): void
    {
        $updateMemberService = $this->getUpdateMemberService();

        $updateMemberService->setPolicy(new Policy([$rule]));

        $this->userDtoMock->id = Uuid::uuid4Generate();

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView($churches));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser($this->userDtoMock->id));

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(UsersLists::showUser($this->userDtoMock->id));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById($this->cityId));

        $this
            ->personsRepositoryMock
            ->method('save')
            ->willReturn(UsersLists::getPersonCreated());

        $this
            ->usersRepositoryMock
            ->method('saveInMembers')
            ->willReturn(UsersLists::showUser());

        $updated = $updateMemberService->execute($this->userDtoMock);

        $this->assertInstanceOf(UpdateMemberResponse::class, $updated);
    }

    public function test_should_return_exception_if_user_member_not_exists()
    {
        $updateMemberService = $this->getUpdateMemberService();

        $updateMemberService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value
        ]));

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $updateMemberService->execute($this->userDtoMock);
    }

    public function test_should_return_exception_if_email_already_exists()
    {
        $updateMemberService = $this->getUpdateMemberService();

        $updateMemberService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value
        ]));

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $updateMemberService->execute($this->userDtoMock);
    }

    public function test_should_return_exception_if_phone_already_exists()
    {
        $updateMemberService = $this->getUpdateMemberService();

        $updateMemberService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value
        ]));

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser($this->userId));

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(UsersLists::showUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $updateMemberService->execute($this->userDtoMock);
    }

    public function test_should_return_exception_if_city_id_not_exists()
    {
        $updateMemberService = $this->getUpdateMemberService();

        $updateMemberService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value
        ]));

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView());

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser($this->userId));

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(UsersLists::showUser($this->userId));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $updateMemberService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderUpdateUserMemberProfilesNotAllowed
     *
     * @param mixed $rule
     * @return void
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function test_should_return_error_if_profile_equals_or_exceeds(
        mixed $rule,
    ): void
    {
        $updateMemberService = $this->getUpdateMemberService();

        $updateMemberService->setPolicy(new Policy([$rule]));

        $this->userDtoMock->id = Uuid::uuid4Generate();

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $updateMemberService->execute($this->userDtoMock);
    }

    /**
     * @dataProvider dataProviderUpdateUserMemberItself
     *
     * @param mixed $rule
     * @param mixed $churches
     * @return void
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function test_should_return_exception_if_user_tries_to_update_a_church_other_than_his(
        string $rule,
        array $churches,
    ): void
    {
        $updateMemberService = $this->getUpdateMemberService();

        $updateMemberService->setPolicy(new Policy([$rule]));

        $churches[0]->id = Uuid::uuid4Generate();

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView($churches));

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser($this->userId));

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(UsersLists::showUser($this->userId));

        $this
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById($this->cityId));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateMemberService->execute($this->userDtoMock);
    }
}
