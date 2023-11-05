<?php

namespace Tests\Unit\App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\ShowByChurchUniqueNameService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\MemberLists;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ShowByChurchUniqueNameServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;
    private string $churchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);

        $this->churchId = Uuid::uuid4Generate();
    }

    public function getShowByChurchUniqueNameService(): ShowByChurchUniqueNameService
    {
        $showByChurchUniqueNameService = new ShowByChurchUniqueNameService(
            $this->churchRepositoryMock
        );

        $showByChurchUniqueNameService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(
                $this->churchId,
                Uuid::uuid4Generate()
            )
        );

        return $showByChurchUniqueNameService;
    }

    public static function dataProviderShowChurch(): array
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
                ChurchLists::showChurch(
                    $this->churchId
                )
            );

        $church = $showByChurchUniqueNameService->execute('test');

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

        $showByChurchUniqueNameService->execute('test');
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
                ChurchLists::showChurch(
                    Uuid::uuid4Generate()
                )
            );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showByChurchUniqueNameService->execute('test');
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

        $showByChurchUniqueNameService->execute('test');
    }
}
