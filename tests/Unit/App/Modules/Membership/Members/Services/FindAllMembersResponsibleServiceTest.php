<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Services\FindAllMembersResponsibleService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\MembersLists;

class FindAllMembersResponsibleServiceTest extends TestCase
{
    private MockObject|MembersRepositoryInterface $membersRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock = $this->createMock(MembersRepository::class);
    }

    public function getFindAllMembersResponsibleService(): FindAllMembersResponsibleService
    {
        return new FindAllMembersResponsibleService($this->membersRepositoryMock);
    }

    public function test_should_return_members_responsible_list()
    {
        $findAllMembersResponsibleService = $this->getFindAllMembersResponsibleService();

        $findAllMembersResponsibleService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value
            ])
        );

        $this
            ->membersRepositoryMock
            ->method('findAllResponsible')
            ->willReturn(MembersLists::getMembersInCreateChurch());

        $members = $findAllMembersResponsibleService->execute();

        $this->assertInstanceOf(Collection::class, $members);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllMembersResponsibleService = $this->getFindAllMembersResponsibleService();

        $findAllMembersResponsibleService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllMembersResponsibleService->execute();
    }
}
