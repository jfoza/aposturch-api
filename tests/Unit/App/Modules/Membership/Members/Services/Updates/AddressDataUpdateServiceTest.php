<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Repositories\CityRepository;
use App\Features\Module\Modules\Models\Module;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Persons\Infra\Repositories\PersonsRepository;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\AddressDataUpdateDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\Updates\AddressDataUpdateService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\CitiesLists;
use Tests\Unit\App\Resources\MemberLists;

class AddressDataUpdateServiceTest extends TestCase
{
    use MembersProvidersTrait;

    protected MockObject|MembersRepositoryInterface  $membersRepositoryMock;
    protected MockObject|PersonsRepositoryInterface  $personsRepositoryMock;
    protected MockObject|CityRepositoryInterface     $cityRepositoryMock;
    protected MockObject|UpdateMemberResponse        $updateMemberResponseMock;

    protected AddressDataUpdateDTO $addressDataUpdateDtoMock;

    private string $churchId;
    private string $moduleId;

    private mixed $churches;
    private mixed $modules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock    = $this->createMock(MembersRepository::class);
        $this->personsRepositoryMock    = $this->createMock(PersonsRepository::class);
        $this->cityRepositoryMock       = $this->createMock(CityRepository::class);
        $this->updateMemberResponseMock = $this->createMock(UpdateMemberResponse::class);

        $this->addressDataUpdateDtoMock = $this->createMock(AddressDataUpdateDTO::class);

        $this->churchId = Uuid::uuid4Generate();
        $this->moduleId = Uuid::uuid4Generate();

        $this->churches = Collection::make([(object) ([Church::ID => $this->churchId])]);
        $this->modules  = Collection::make([(object) ([Module::ID => $this->moduleId])]);
    }

    public function getAddressDataUpdateService(): AddressDataUpdateService
    {
        return new AddressDataUpdateService(
            $this->membersRepositoryMock,
            $this->personsRepositoryMock,
            $this->cityRepositoryMock,
            $this->updateMemberResponseMock,
        );
    }

    public function populateAddressDataUpdateDTO(): void
    {
        $this->addressDataUpdateDtoMock->id = Uuid::uuid4Generate();
        $this->addressDataUpdateDtoMock->zipCode = '00000000';
        $this->addressDataUpdateDtoMock->address = 'test';
        $this->addressDataUpdateDtoMock->numberAddress = '00';
        $this->addressDataUpdateDtoMock->complement = 'test';
        $this->addressDataUpdateDtoMock->district = 'test';
        $this->addressDataUpdateDtoMock->cityId = Uuid::uuid4Generate();
        $this->addressDataUpdateDtoMock->uf = 'RS';
    }

    /**
     * @dataProvider dataProviderUpdate
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_update_member_address_data(
        string $rule
    ): void
    {
        $addressDataUpdateService = $this->getAddressDataUpdateService();

        $addressDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $addressDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateAddressDataUpdateDTO();

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
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(CitiesLists::showCityById(Uuid::uuid4Generate()));

        $this
            ->personsRepositoryMock
            ->method('saveAddress')
            ->willReturn(MemberLists::getPerson());

        $updated = $addressDataUpdateService->execute($this->addressDataUpdateDtoMock);

        $this->assertInstanceOf(UpdateMemberResponse::class, $updated);
    }

    /**
     * @dataProvider dataProviderUpdate
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_city_not_exists(
        string $rule
    ): void
    {
        $addressDataUpdateService = $this->getAddressDataUpdateService();

        $addressDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $addressDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateAddressDataUpdateDTO();

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
            ->cityRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CITY_NOT_FOUND));

        $addressDataUpdateService->execute($this->addressDataUpdateDtoMock);
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
        $addressDataUpdateService = $this->getAddressDataUpdateService();

        $addressDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $addressDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateAddressDataUpdateDTO();

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $addressDataUpdateService->execute($this->addressDataUpdateDtoMock);
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
        $addressDataUpdateService = $this->getAddressDataUpdateService();

        $addressDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $addressDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        $this->populateAddressDataUpdateDTO();

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

        $addressDataUpdateService->execute($this->addressDataUpdateDtoMock);
    }

    /**
     * @dataProvider dataProviderUpdateMemberValidationProfileAndModules
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_authenticated_user_does_not_have_access_to_module(
        string $rule,
    ): void
    {
        $addressDataUpdateService = $this->getAddressDataUpdateService();

        $addressDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $addressDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                Uuid::uuid4Generate(),
            )
        );

        $this->populateAddressDataUpdateDTO();

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

        $addressDataUpdateService->execute($this->addressDataUpdateDtoMock);
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
        $addressDataUpdateService = $this->getAddressDataUpdateService();

        $addressDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $addressDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                Uuid::uuid4Generate(),
                $this->moduleId,
            )
        );

        $this->populateAddressDataUpdateDTO();

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

        $addressDataUpdateService->execute($this->addressDataUpdateDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $addressDataUpdateService = $this->getAddressDataUpdateService();

        $addressDataUpdateService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $addressDataUpdateService->execute($this->addressDataUpdateDtoMock);
    }
}
