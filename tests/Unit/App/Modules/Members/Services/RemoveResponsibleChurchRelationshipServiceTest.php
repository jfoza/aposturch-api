<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Modules\Members\Church\Services\RemoveResponsibleChurchRelationshipService;
use App\Modules\Members\ResponsibleChurch\Contracts\ResponsibleChurchRepositoryInterface;
use App\Modules\Members\ResponsibleChurch\Repositories\ResponsibleChurchRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;

class RemoveResponsibleChurchRelationshipServiceTest extends TestCase
{
    private MockObject|ResponsibleChurchRepositoryInterface $responsibleChurchRepositoryMock;

    private string $adminUserId;
    private string $churchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->responsibleChurchRepositoryMock = $this->createMock(ResponsibleChurchRepository::class);

        $this->adminUserId = Uuid::uuid4()->toString();
        $this->churchId = Uuid::uuid4()->toString();
    }

    public function getRemoveResponsibleChurchRelationshipService(): RemoveResponsibleChurchRelationshipService
    {
        return new RemoveResponsibleChurchRelationshipService(
            $this->responsibleChurchRepositoryMock
        );
    }

    public function test_should_remove_unique_church()
    {
        $removeResponsibleChurchRelationshipService = $this->getRemoveResponsibleChurchRelationshipService();

        $removeResponsibleChurchRelationshipService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_USER_RELATIONSHIP_DELETE->value
            ])
        );

        $this
            ->responsibleChurchRepositoryMock
            ->method('findByAdminUserAndChurch')
            ->willReturn(ChurchLists::getRelationAdminUserChurch());

        $removeResponsibleChurchRelationshipService->execute(
            $this->adminUserId,
            $this->churchId,
        );

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_relationship_not_exists()
    {
        $removeResponsibleChurchRelationshipService = $this->getRemoveResponsibleChurchRelationshipService();

        $removeResponsibleChurchRelationshipService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_USER_RELATIONSHIP_DELETE->value
            ])
        );

        $this
            ->responsibleChurchRepositoryMock
            ->method('findByAdminUserAndChurch')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $removeResponsibleChurchRelationshipService->execute(
            $this->adminUserId,
            $this->churchId,
        );
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeResponsibleChurchRelationshipService = $this->getRemoveResponsibleChurchRelationshipService();

        $removeResponsibleChurchRelationshipService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeResponsibleChurchRelationshipService->execute(
            $this->adminUserId,
            $this->churchId,
        );
    }
}
