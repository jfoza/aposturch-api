<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\Updates\ChurchDataUpdateService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Modules\Membership\Members\MembersProvidersTrait;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\MemberLists;

class ChurchDataUpdateServiceTest extends TestCase
{
    use MembersProvidersTrait;

    protected MockObject|MembersRepositoryInterface $membersRepositoryMock;
    protected MockObject|ChurchRepositoryInterface  $churchRepositoryMock;
    protected MockObject|UpdateMemberResponse       $updateMemberResponseMock;

    protected string $churchId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membersRepositoryMock    = $this->createMock(MembersRepository::class);
        $this->churchRepositoryMock     = $this->createMock(ChurchRepository::class);
        $this->updateMemberResponseMock = $this->createMock(UpdateMemberResponse::class);

        $this->churchId = Uuid::uuid4Generate();
    }

    public function getChurchDataUpdateService(): ChurchDataUpdateService
    {
        return new ChurchDataUpdateService(
            $this->membersRepositoryMock,
            $this->churchRepositoryMock,
            $this->updateMemberResponseMock,
        );
    }

    /**
     * @dataProvider dataProviderUpdateChurchData
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_update_church_data_user_member(
        string $rule
    ): void
    {
        $churchDataUpdateService = $this->getChurchDataUpdateService();

        $churchDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $churchDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->churchId));

        $userId = Uuid::uuid4Generate();

        $updated = $churchDataUpdateService->execute($userId, $this->churchId);

        $this->assertInstanceOf(UpdateMemberResponse::class, $updated);
    }

    /**
     * @dataProvider dataProviderUpdateChurchData
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_church_not_exists(
        string $rule
    ): void
    {
        $churchDataUpdateService = $this->getChurchDataUpdateService();

        $churchDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $churchDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => $this->churchId])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $userId = Uuid::uuid4Generate();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::CHURCH_NOT_FOUND));

        $churchDataUpdateService->execute($userId, $this->churchId);
    }

    /**
     * @dataProvider dataProviderUpdateChurchData
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_exception_if_user_not_exists(
        string $rule
    ): void
    {
        $churchDataUpdateService = $this->getChurchDataUpdateService();

        $churchDataUpdateService->setPolicy(
            new Policy([$rule])
        );

        $churchDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged($this->churchId)
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(null);

        $userId = Uuid::uuid4Generate();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::USER_NOT_FOUND));

        $churchDataUpdateService->execute($userId, $this->churchId);
    }

    public function test_should_return_exception_if_the_authenticated_user_is_not_linked_to_the_churches()
    {
        $churchDataUpdateService = $this->getChurchDataUpdateService();

        $churchDataUpdateService->setPolicy(
            new Policy([RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value])
        );

        $churchDataUpdateService->setAuthenticatedUser(
            MemberLists::getMemberUserLogged(Uuid::uuid4Generate())
        );

        $this
            ->membersRepositoryMock
            ->method('findByUserId')
            ->willReturn(
                MemberLists::getMemberDataView(
                    Collection::make([(object) ([Church::ID => Uuid::uuid4Generate()])]),
                    ProfileUniqueNameEnum::ASSISTANT->value
                )
            );

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch(Uuid::uuid4Generate()));

        $userId = Uuid::uuid4Generate();
        $churchId = Uuid::uuid4Generate();

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
        $this->expectExceptionMessage(json_encode(MessagesEnum::NO_ACCESS_TO_CHURCH_MEMBERS));

        $churchDataUpdateService->execute($userId, $churchId);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $churchDataUpdateService = $this->getChurchDataUpdateService();

        $churchDataUpdateService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $userId = Uuid::uuid4Generate();

        $churchDataUpdateService->execute($userId, $this->churchId);
    }
}
