<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Modules\Membership\Church\Services\RemoveMemberChurchRelationshipService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\UsersLists;

class RemoveUserChurchRelationshipServiceTest extends TestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
    private string $churchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock = $this->createMock(UsersRepository::class);

        $this->churchId = Uuid::uuid4()->toString();
    }

    public function getRemoveUserChurchRelationshipService(): RemoveMemberChurchRelationshipService
    {
        return new RemoveMemberChurchRelationshipService(
            $this->usersRepositoryMock
        );
    }

    public function dataProviderRemoveChurchUserRelationship(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_USER_RELATIONSHIP_DELETE->value],
            'By Admin Church Rule' => [RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_USER_RELATIONSHIP_DELETE->value],
        ];
    }

    /**
     * @dataProvider dataProviderRemoveChurchUserRelationship
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_remove_unique_church(string $rule): void
    {
        $removeUserChurchRelationshipService = $this->getRemoveUserChurchRelationshipService();

        $removeUserChurchRelationshipService->setPolicy(
            new Policy([$rule])
        );

        $removeUserChurchRelationshipService->setResponsibleChurch(
            ChurchLists::getChurchesById($this->churchId)
        );

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUserChurch($this->churchId));

        $removeUserChurchRelationshipService->execute($this->churchId);

        $this->assertTrue(true);
    }

    public function test_should_return_exception_if_user_tries_to_delete_a_church_relationship_other_than_his()
    {
        $removeUserChurchRelationshipService = $this->getRemoveUserChurchRelationshipService();

        $removeUserChurchRelationshipService->setPolicy(
            new Policy([RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_USER_RELATIONSHIP_DELETE->value])
        );

        $removeUserChurchRelationshipService->setResponsibleChurch(
            ChurchLists::getChurchesById('abc')
        );

        $this
            ->usersRepositoryMock
            ->method('findById')
            ->willReturn(UsersLists::showUserChurch($this->churchId));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeUserChurchRelationshipService->execute($this->churchId);
    }

    public function test_should_return_exception_if_church_id_not_exists()
    {
        $removeUserChurchRelationshipService = $this->getRemoveUserChurchRelationshipService();

        $removeUserChurchRelationshipService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_USER_RELATIONSHIP_DELETE->value
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
