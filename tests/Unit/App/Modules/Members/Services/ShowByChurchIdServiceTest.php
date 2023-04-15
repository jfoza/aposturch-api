<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Repositories\ChurchRepository;
use App\Modules\Members\Church\Services\ShowByChurchIdService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;

class ShowByChurchIdServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;

    private string $churchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);

        $this->churchId = Uuid::uuid4()->toString();
    }

    public function getShowByChurchIdService(): ShowByChurchIdService
    {
        return new ShowByChurchIdService(
            $this->churchRepositoryMock
        );
    }

    public function dataProviderShowChurch(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_VIEW->value],
            'By Admin Church Rule' => [RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderShowChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_to_return_unique_church(string $rule): void
    {
        $showByChurchIdService = $this->getShowByChurchIdService();

        $showByChurchIdService->setPolicy(
            new Policy([$rule])
        );

        $showByChurchIdService->setChurchUserAuth(ChurchLists::showChurch($this->churchId));

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $church = $showByChurchIdService->execute($this->churchId);

        $this->assertIsObject($church);
    }

    public function test_should_return_exception_if_church_id_not_exists()
    {
        $showByChurchIdService = $this->getShowByChurchIdService();

        $showByChurchIdService->setPolicy(
            new Policy([RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_VIEW->value])
        );

        $showByChurchIdService->setChurchUserAuth(ChurchLists::showChurch($this->churchId));

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $showByChurchIdService->execute($this->churchId);
    }

    public function test_should_return_exception_if_user_tries_to_view_a_church_other_than_his()
    {
        $showByChurchIdService = $this->getShowByChurchIdService();

        $showByChurchIdService->setPolicy(
            new Policy([RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_VIEW->value])
        );

        $showByChurchIdService->setChurchUserAuth(ChurchLists::showChurch());

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showByChurchIdService->execute($this->churchId);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showByChurchIdService = $this->getShowByChurchIdService();

        $showByChurchIdService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showByChurchIdService->execute(Uuid::uuid4()->toString());
    }
}
