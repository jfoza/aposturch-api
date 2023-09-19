<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Models\Module;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Persons\Infra\Repositories\PersonsRepository;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\GeneralDataUpdateDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\Updates\GeneralDataUpdateService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\UsersLists;

class GeneralDataUpdateServiceTest extends TestCase
{
    use MembersProvidersTrait;

    protected MockObject|MembersRepositoryInterface  $membersRepositoryMock;
    protected MockObject|PersonsRepositoryInterface  $personsRepositoryMock;
    protected MockObject|UsersRepositoryInterface    $usersRepositoryMock;
    protected MockObject|UpdateMemberResponse        $updateMemberResponseMock;

    protected MockObject|GeneralDataUpdateDTO $generalDataUpdateDtoMock;

    private string $churchId;
    private string $moduleId;

    private mixed $churches;
    private mixed $modules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock    = $this->createMock(MembersRepository::class);
        $this->personsRepositoryMock    = $this->createMock(PersonsRepository::class);
        $this->usersRepositoryMock      = $this->createMock(UsersRepository::class);
        $this->updateMemberResponseMock = $this->createMock(UpdateMemberResponse::class);

        $this->generalDataUpdateDtoMock = $this->createMock(GeneralDataUpdateDTO::class);

        $this->churchId = Uuid::uuid4Generate();
        $this->moduleId = Uuid::uuid4Generate();

        $this->churches = Collection::make([(object) ([Church::ID => $this->churchId])]);
        $this->modules  = Collection::make([(object) ([Module::ID => $this->moduleId])]);
    }

    public function getGeneralDataUpdateService(): GeneralDataUpdateService
    {
        return new GeneralDataUpdateService(
            $this->membersRepositoryMock,
            $this->personsRepositoryMock,
            $this->usersRepositoryMock,
            $this->updateMemberResponseMock,
        );
    }

    public function populateGeneralDataUpdateDTO(): void
    {
        $this->generalDataUpdateDtoMock->id = Uuid::uuid4Generate();
        $this->generalDataUpdateDtoMock->name = 'test';
        $this->generalDataUpdateDtoMock->email = 'test@test.com';
        $this->generalDataUpdateDtoMock->phone = '5199999999';
    }

    /**
     * @dataProvider dataProviderUpdate
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_update_user_member_general_data(
        string $rule
    ): void
    {
        $generalDataUpdateService = $this->getGeneralDataUpdateService();

        $generalDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $generalDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateGeneralDataUpdateDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    $this->modules,
                    ProfileUniqueNameEnum::ASSISTANT->value,
                )
            );

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(null);

        $this
            ->usersRepositoryMock
            ->method('findByPhone')
            ->willReturn(null);

        $this
            ->personsRepositoryMock
            ->method('savePhone')
            ->willReturn(MemberLists::getPerson());

        $this
            ->usersRepositoryMock
            ->method('saveInMembers')
            ->willReturn(UsersLists::showUser());

        $updated = $generalDataUpdateService->execute($this->generalDataUpdateDtoMock);

        $this->assertInstanceOf(UpdateMemberResponse::class, $updated);
    }

    /**
     * @dataProvider dataProviderUpdate
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_email_already_exists(
        string $rule
    ): void
    {
        $generalDataUpdateService = $this->getGeneralDataUpdateService();

        $generalDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $generalDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateGeneralDataUpdateDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    $this->modules,
                    ProfileUniqueNameEnum::ASSISTANT->value,
                )
            );

        $this
            ->usersRepositoryMock
            ->method('findByEmail')
            ->willReturn(UsersLists::showUser());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage(json_encode(MessagesEnum::EMAIL_ALREADY_EXISTS));

        $generalDataUpdateService->execute($this->generalDataUpdateDtoMock);
    }

    /**
     * @dataProvider dataProviderUpdate
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_phone_already_exists(
        string $rule
    ): void
    {
        $generalDataUpdateService = $this->getGeneralDataUpdateService();

        $generalDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $generalDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateGeneralDataUpdateDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    $this->modules,
                    ProfileUniqueNameEnum::ASSISTANT->value,
                )
            );

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
        $this->expectExceptionMessage(json_encode(MessagesEnum::PHONE_ALREADY_EXISTS));

        $generalDataUpdateService->execute($this->generalDataUpdateDtoMock);
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
        $generalDataUpdateService = $this->getGeneralDataUpdateService();

        $generalDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $generalDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateGeneralDataUpdateDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $generalDataUpdateService->execute($this->generalDataUpdateDtoMock);
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
        $generalDataUpdateService = $this->getGeneralDataUpdateService();

        $generalDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $generalDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateGeneralDataUpdateDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    $this->churches,
                    $this->modules,
                    ProfileUniqueNameEnum::ADMIN_CHURCH->value,
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::PROFILE_NOT_ALLOWED));

        $generalDataUpdateService->execute($this->generalDataUpdateDtoMock);
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
        $generalDataUpdateService = $this->getGeneralDataUpdateService();

        $generalDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $generalDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                Uuid::uuid4Generate(),
                $this->moduleId,
            )
        );

        $this->populateGeneralDataUpdateDTO();

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

        $generalDataUpdateService->execute($this->generalDataUpdateDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $generalDataUpdateService = $this->getGeneralDataUpdateService();

        $generalDataUpdateService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $generalDataUpdateService->execute($this->generalDataUpdateDtoMock);
    }
}
