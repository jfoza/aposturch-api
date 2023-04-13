<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Infra\Repositories\UsersRepository;
use App\Modules\Members\Church\Services\RemoveUserChurchRelationshipService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\UsersLists;

class RemoveUserChurchRelationshipServiceTest extends TestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock = $this->createMock(UsersRepository::class);
    }

    public function getRemoveUserChurchRelationshipService(): RemoveUserChurchRelationshipService
    {
        return new RemoveUserChurchRelationshipService(
            $this->usersRepositoryMock
        );
    }

    public function test_should_remove_unique_church()
    {
        $removeUserChurchRelationshipService = $this->getRemoveUserChurchRelationshipService();

        $removeUserChurchRelationshipService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_USER_RELATIONSHIP_DELETE->value
            ])
        );

        $id = Uuid::uuid4()->toString();

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUser());

        $removeUserChurchRelationshipService->execute($id);

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_church_id_not_exists()
    {
        $removeUserChurchRelationshipService = $this->getRemoveUserChurchRelationshipService();

        $removeUserChurchRelationshipService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_USER_RELATIONSHIP_DELETE->value
            ])
        );

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $removeUserChurchRelationshipService->execute(Uuid::uuid4()->toString());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeUserChurchRelationshipService = $this->getRemoveUserChurchRelationshipService();

        $removeUserChurchRelationshipService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeUserChurchRelationshipService->execute(Uuid::uuid4()->toString());
    }
}
