<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\Infra\Repositories\ImagesRepository;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\RemoveChurchService;
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
    private MockObject|ImagesRepositoryInterface $imagesRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);
        $this->imagesRepository     = $this->createMock(ImagesRepository::class);
    }

    public function getRemoveChurchService(): RemoveChurchService
    {
        return new RemoveChurchService(
            $this->churchRepositoryMock,
            $this->imagesRepository
        );
    }

    public function test_should_remove_unique_church()
    {
        $removeChurchService = $this->getRemoveChurchService();

        $removeChurchService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE->value
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

    public function test_should_remove_unique_church_with_image()
    {
        $removeChurchService = $this->getRemoveChurchService();

        $removeChurchService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE->value
            ])
        );

        $id = Uuid::uuid4()->toString();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurchWithImage());

        $removeChurchService->execute($id);

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_church_id_not_exists()
    {
        $removeChurchService = $this->getRemoveChurchService();

        $removeChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE->value
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
            RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE->value
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
