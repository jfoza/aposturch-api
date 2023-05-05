<?php

namespace Tests\Unit\App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\ShowByChurchUniqueNameService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\MembersLists;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowByChurchUniqueNameServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;

    private string $churchUniqueName;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);

        $this->churchUniqueName = 'church-test-unique-name';

        JWTAuth::shouldReceive('user')->andreturn(MembersLists::getMemberUserLogged(null, $this->churchUniqueName));
        Auth::shouldReceive('user')->andreturn(MembersLists::getMemberUserLogged(null, $this->churchUniqueName));
    }

    public function getShowByChurchUniqueNameService(): ShowByChurchUniqueNameService
    {
        return new ShowByChurchUniqueNameService(
            $this->churchRepositoryMock
        );
    }

    public function dataProviderShowChurch(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW->value],
            'By Admin Church Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderShowChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException|UserNotDefinedException
     */
    public function test_should_to_return_unique_church(string $rule): void
    {
        $showByChurchUniqueNameService = $this->getShowByChurchUniqueNameService();

        $showByChurchUniqueNameService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->churchRepositoryMock
            ->method('findByUniqueName')
            ->willReturn(
                ChurchLists::showChurchByUniqueName($this->churchUniqueName)
            );

        $church = $showByChurchUniqueNameService->execute($this->churchUniqueName);

        $this->assertIsObject($church);
    }

    public function test_should_return_exception_if_church_unique_name_not_exists()
    {
        $showByChurchUniqueNameService = $this->getShowByChurchUniqueNameService();

        $showByChurchUniqueNameService->setPolicy(
            new Policy([RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW->value])
        );

        $this
            ->churchRepositoryMock
            ->method('findByUniqueName')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $showByChurchUniqueNameService->execute($this->churchUniqueName);
    }

    public function test_should_return_exception_if_user_tries_to_view_a_church_other_than_his()
    {
        $showByChurchUniqueNameService = $this->getShowByChurchUniqueNameService();

        $showByChurchUniqueNameService->setPolicy(
            new Policy([RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW->value])
        );

        $this
            ->churchRepositoryMock
            ->method('findByUniqueName')
            ->willReturn(
                ChurchLists::showChurchByUniqueName()
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showByChurchUniqueNameService->execute($this->churchUniqueName);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showByChurchUniqueNameService = $this->getShowByChurchUniqueNameService();

        $showByChurchUniqueNameService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showByChurchUniqueNameService->execute($this->churchUniqueName);
    }
}
