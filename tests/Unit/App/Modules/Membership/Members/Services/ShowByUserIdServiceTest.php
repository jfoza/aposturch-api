<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\MemberResponse;
use App\Modules\Membership\Members\Services\ShowByUserIdService;
use App\Shared\ACL\Policy;
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
    private MockObject|MembersFiltersDTO $membersFiltersDtoMock;
    private MockObject|MemberResponse    $memberResponseMock;

    private string $churchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);
        $this->membersFiltersDtoMock = $this->createMock(MembersFiltersDTO::class);
        $this->memberResponseMock    = $this->createMock(MemberResponse::class);

        $this->churchId  = Uuid::uuid4Generate();
    }

    public function getShowByUserIdService(): ShowByUserIdService
    {
        $showByUserIdService = new ShowByUserIdService(
            $this->membersRepositoryMock,
            $this->membersFiltersDtoMock,
            $this->memberResponseMock
        );

        $showByUserIdService->setAuthenticatedUser(MemberLists::getMemberUserLogged($this->churchId));

        return $showByUserIdService;
    }

    public function dataProviderMembersRule(): array
    {
        return [
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS_VIEW->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW->value],
        ];
    }

    public function test_should_to_list_unique_user_member_by_admin_master_rule()
    {
        $showByUserIdService = $this->getShowByUserIdService();

        $showByUserIdService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS_VIEW->value
            ])
        );

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView());

        $userMember = $showByUserIdService->execute(Uuid::uuid4Generate());

        $this->assertIsObject($userMember);
    }

    /**
     * @dataProvider dataProviderMembersRule
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_to_list_unique_user_member_by_members_rule(string $rule): void
    {
        $showByUserIdService = $this->getShowByUserIdService();

        $showByUserIdService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView(
                Collection::make([
                    (object) ([Church::ID => $this->churchId])
                ])
            ));

        $userMember = $showByUserIdService->execute(Uuid::uuid4Generate());

        $this->assertIsObject($userMember);
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
            ->method('findOneByFilters')
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
