<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\MemberResponse;
use App\Modules\Membership\Members\Services\ShowByUserIdService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\MemberLists;

class ShowByUserIdServiceTest extends TestCase
{
    private MockObject|MembersRepositoryInterface $membersRepositoryMock;
    private MockObject|MemberResponse $memberResponseMock;

    private string $churchId;
    private string $moduleId;

    private mixed $churches;
    private mixed $modules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);
        $this->membersFiltersDtoMock = $this->createMock(MembersFiltersDTO::class);
        $this->memberResponseMock    = $this->createMock(MemberResponse::class);

        $this->churchId = Uuid::uuid4Generate();
        $this->moduleId = Uuid::uuid4Generate();

        $this->churches = Collection::make([(object) ([Church::ID => $this->churchId])]);
        $this->modules  = Collection::make([(object) ([Module::ID => $this->moduleId])]);
    }

    public function getShowByUserIdService(): ShowByUserIdService
    {
        $showByUserIdService = new ShowByUserIdService(
            $this->membersRepositoryMock,
            $this->memberResponseMock
        );

        $showByUserIdService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                $this->moduleId,
            )
        );

        return $showByUserIdService;
    }

    public static function dataProviderMembersRule(): array
    {
        return [
            'By Admin Master' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS_VIEW->value],
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS_VIEW->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW->value],
        ];
    }

    public static function dataProviderMembersChurchValidations(): array
    {
        return [
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS_VIEW->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW->value],
        ];
    }

    public static function dataProviderMembersProfileModulesValidations(): array
    {
        return [
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderMembersRule
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_to_list_unique_user_member(
        string $rule
    ): void
    {
        $showByUserIdService = $this->getShowByUserIdService();

        $showByUserIdService->setPolicy(
            new Policy([$rule])
        );

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

        $userMember = $showByUserIdService->execute(Uuid::uuid4Generate());

        $this->assertIsObject($userMember);
    }

    /**
     * @dataProvider dataProviderMembersProfileModulesValidations
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_user_tries_to_view_a_superior_profile_in_members(
        string $rule
    ): void
    {
        $showByUserIdService = $this->getShowByUserIdService();

        $showByUserIdService->setPolicy(
            new Policy([$rule])
        );

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

        $showByUserIdService->execute(Uuid::uuid4Generate());
    }

    /**
     * @dataProvider dataProviderMembersProfileModulesValidations
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_authenticated_user_does_not_have_access_to_module(
        string $rule,
    ): void
    {
        $showByUserIdService = $this->getShowByUserIdService();

        $showByUserIdService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                Uuid::uuid4Generate(),
            )
        );

        $showByUserIdService->setPolicy(
            new Policy([$rule])
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

        $showByUserIdService->execute(Uuid::uuid4Generate());
    }

    /**
     * @dataProvider dataProviderMembersChurchValidations
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_the_authenticated_user_is_not_linked_to_the_churches(
        string $rule
    ): void
    {
        $showByUserIdService = $this->getShowByUserIdService();

        $showByUserIdService->setPolicy(
            new Policy([$rule])
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

        $showByUserIdService->execute(Uuid::uuid4Generate());
    }

    /**
     * @dataProvider dataProviderMembersRule
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_exception_if_user_member_not_exists(string $rule): void
    {
        $showByUserIdService = $this->getShowByUserIdService();

        $showByUserIdService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $showByUserIdService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showByUserIdService = $this->getShowByUserIdService();

        $showByUserIdService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showByUserIdService->execute(Uuid::uuid4Generate());
    }
}
