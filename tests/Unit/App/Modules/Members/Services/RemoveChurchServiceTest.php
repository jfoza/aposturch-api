<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Repositories\ChurchRepository;
use App\Modules\Members\Church\Services\RemoveChurchService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;

class RemoveChurchServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);
    }

    public function getRemoveChurchService(): RemoveChurchService
    {
        return new RemoveChurchService(
            $this->churchRepositoryMock
        );
    }

    public function test_should_remove_unique_church()
    {
        $removeChurchService = $this->getRemoveChurchService();

        $removeChurchService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_DELETE->value
            ])
        );

        $id = Uuid::uuid4()->toString();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch());

        $removeChurchService->execute($id);

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_church_id_not_exists()
    {
        $removeChurchService = $this->getRemoveChurchService();

        $removeChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_DELETE->value
        ]));

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $removeChurchService->execute(Uuid::uuid4()->toString());
    }

    public function test_should_return_exception_if_church_has_members()
    {
        $removeChurchService = $this->getRemoveChurchService();

        $removeChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_DELETE->value
        ]));

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurchWithMembers());

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_BAD_REQUEST);

        $removeChurchService->execute(Uuid::uuid4()->toString());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeChurchService = $this->getRemoveChurchService();

        $removeChurchService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeChurchService->execute(Uuid::uuid4()->toString());
    }
}