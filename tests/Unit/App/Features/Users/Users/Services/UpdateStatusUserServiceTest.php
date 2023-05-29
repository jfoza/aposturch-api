<?php

namespace Tests\Unit\App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Features\Users\Users\Services\UpdateStatusUserService;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\MemberLists;
use Tests\Unit\App\Resources\UsersLists;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateStatusUserServiceTest extends TestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
    private MockObject|MembersRepositoryInterface $membersRepositoryMock;
    private MockObject|MembersFiltersDTO $membersFiltersDtoMock;

    private string $userId;
    private string $defaultChurchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock   = $this->createMock(UsersRepository::class);
        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);
        $this->membersFiltersDtoMock = $this->createMock(MembersFiltersDTO::class);

        $this->userId = Uuid::uuid4Generate();
        $this->defaultChurchId = Uuid::uuid4Generate();

        JWTAuth::shouldReceive('user')->andreturn(MemberLists::getMemberUserLogged($this->defaultChurchId));
        Auth::shouldReceive('user')->andreturn(MemberLists::getMemberUserLogged($this->defaultChurchId));

        JWTAuth::shouldReceive('id')->andreturn($this->userId);
        Auth::shouldReceive('id')->andreturn($this->userId);
    }

    public function getUpdateStatusUserService(): UpdateStatusUserService
    {
        return new UpdateStatusUserService(
            $this->usersRepositoryMock,
            $this->membersRepositoryMock,
            $this->membersFiltersDtoMock,
        );
    }

    public function test_should_update_status_by_admin_master()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setPolicy(new Policy([
            RulesEnum::USERS_ADMIN_MASTER_UPDATE_STATUS->value
        ]));

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser($this->userId));

        $result = $updateStatusUserService->execute($this->userId);

        $this->assertIsArray($result);
    }

    public function test_should_update_status_by_admin_church()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setPolicy(new Policy([
            RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value
        ]));

        $church = [
            (object)([
                Church::ID          => $this->defaultChurchId,
                Church::NAME        => "Igreja Teste 1",
                Church::UNIQUE_NAME => "igreja-teste-1",
                Church::PHONE       => "51999999999",
                Church::EMAIL       => "ibvcx@gmail.com",
                Church::ACTIVE      => true,
            ])
        ];

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView($church));

        $result = $updateStatusUserService->execute(Uuid::uuid4Generate());

        $this->assertIsArray($result);
    }

    public function test_should_update_status_user_member_itself()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setPolicy(new Policy([
            RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value
        ]));

        $church = [
            (object)([
                Church::ID          => $this->defaultChurchId,
                Church::NAME        => "Igreja Teste 1",
                Church::UNIQUE_NAME => "igreja-teste-1",
                Church::PHONE       => "51999999999",
                Church::EMAIL       => "ibvcx@gmail.com",
                Church::ACTIVE      => true,
            ])
        ];

        $this
            ->membersRepositoryMock
            ->method('findOneByFilters')
            ->willReturn(MemberLists::getMemberDataView($church));

        $result = $updateStatusUserService->execute($this->userId);

        $this->assertIsArray($result);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateStatusUserService = $this->getUpdateStatusUserService();

        $updateStatusUserService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateStatusUserService->execute(Uuid::uuid4Generate());
    }
}
