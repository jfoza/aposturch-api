<?php

namespace Tests\Unit\App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\DTO\ChurchFiltersDTO;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\FindAllChurchesByUserLoggedService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\MemberLists;
use Tymon\JWTAuth\Facades\JWTAuth;

class FindAllChurchesByUserLoggedServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;
    private MockObject|ChurchFiltersDTO $churchFiltersDtoMock;

    private string $churchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);
        $this->churchFiltersDtoMock = $this->createMock(ChurchFiltersDTO::class);

        $this->churchId = Uuid::uuid4()->toString();

        JWTAuth::shouldReceive('user')->andreturn(MemberLists::getMemberUserLogged($this->churchId));
        Auth::shouldReceive('user')->andreturn(MemberLists::getMemberUserLogged($this->churchId));
    }

    public function getFindAllChurchesByUserLoggedService(): FindAllChurchesByUserLoggedService
    {
        return new FindAllChurchesByUserLoggedService(
            $this->churchRepositoryMock,
            $this->churchFiltersDtoMock
        );
    }

    public function test_should_return_churches_user_logged_list_by_admin_master()
    {
        $findAllChurchesByUserLoggedService = $this->getFindAllChurchesByUserLoggedService();

        $findAllChurchesByUserLoggedService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_ADMIN_MASTER_VIEW->value
        ]));

        $this
            ->churchRepositoryMock
            ->method('findAll')
            ->willReturn(ChurchLists::getChurches());

        $churches = $findAllChurchesByUserLoggedService->execute();

        $this->assertInstanceOf(Collection::class, $churches);
    }

    public function test_should_return_churches_user_logged_list_by_general_users()
    {
        $findAllChurchesByUserLoggedService = $this->getFindAllChurchesByUserLoggedService();

        $findAllChurchesByUserLoggedService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_VIEW->value
        ]));

        $this->churchFiltersDtoMock->churchIds = [
            $this->churchId
        ];

        $this->churchFiltersDtoMock->active = true;

        $this
            ->churchRepositoryMock
            ->method('findAll')
            ->willReturn(ChurchLists::getChurches());

        $churches = $findAllChurchesByUserLoggedService->execute();

        $this->assertInstanceOf(Collection::class, $churches);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllChurchesByUserLoggedService = $this->getFindAllChurchesByUserLoggedService();

        $findAllChurchesByUserLoggedService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllChurchesByUserLoggedService->execute();
    }
}
