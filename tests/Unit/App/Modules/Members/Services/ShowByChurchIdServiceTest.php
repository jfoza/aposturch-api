<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Models\Church;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);
    }

    public function getShowByChurchIdService(): ShowByChurchIdService
    {
        return new ShowByChurchIdService(
            $this->churchRepositoryMock
        );
    }

    public function test_should_return_unique_church()
    {
        $showByChurchIdService = $this->getShowByChurchIdService();

        $showByChurchIdService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_VIEW->value
            ])
        );

        $id = Uuid::uuid4()->toString();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($id));

        $church = $showByChurchIdService->execute($id);

        $this->assertInstanceOf(Church::class, $church);
    }

    public function test_should_return_exception_if_church_id_not_exists()
    {
        $showByChurchIdService = $this->getShowByChurchIdService();

        $showByChurchIdService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_VIEW->value
            ])
        );

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $showByChurchIdService->execute(Uuid::uuid4()->toString());
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
