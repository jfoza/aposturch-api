<?php

namespace Tests\Unit\App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserFiltersDTO;
use App\Features\Users\Users\Infra\Repositories\UsersRepository;
use App\Features\Users\Users\Services\FindUsersByChurchService;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Repositories\ChurchRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;
use Tests\Unit\App\Resources\UsersLists;

class FindUsersByChurchServiceTest extends TestCase
{
    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;
    private UserFiltersDTO $userFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usersRepositoryMock  = $this->createMock(UsersRepository::class);
        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);

        $this->userFiltersDtoMock = $this->createMock(UserFiltersDTO::class);

        $this->userFiltersDtoMock->churchId = Uuid::uuid4()->toString();
    }

    public function getFindUsersByChurchService(): FindUsersByChurchService
    {
        return new FindUsersByChurchService(
            $this->usersRepositoryMock ,
            $this->churchRepositoryMock
        );
    }

    public function dataProviderUsersChurch(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW->value],
            'By Admin Church Rule' => [RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderUsersChurch
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_users_by_church_list(string $rule): void
    {
        $findUsersByChurchService = $this->getFindUsersByChurchService();

        $findUsersByChurchService->setPolicy(
            new Policy([$rule])
        );

        $findUsersByChurchService->setResponsibleChurch(
            ChurchLists::getChurchesById($this->userFiltersDtoMock->churchId)
        );

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->userFiltersDtoMock->churchId));

        $this
            ->usersRepositoryMock
            ->method('findAllByChurch')
            ->willReturn(UsersLists::findAllUsers());

        $users = $findUsersByChurchService->execute($this->userFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $users);
    }

    public function test_should_return_exception_if_church_not_exists()
    {
        $findUsersByChurchService = $this->getFindUsersByChurchService();

        $findUsersByChurchService->setPolicy(
            new Policy([RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW->value])
        );

        $findUsersByChurchService->setResponsibleChurch(
            ChurchLists::getChurchesById($this->userFiltersDtoMock->churchId)
        );

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $findUsersByChurchService->execute($this->userFiltersDtoMock);
    }

    public function test_should_return_exception_if_user_tries_to_view_a_church_other_than_his()
    {
        $findUsersByChurchService = $this->getFindUsersByChurchService();

        $findUsersByChurchService->setPolicy(
            new Policy([RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW->value])
        );

        $findUsersByChurchService->setResponsibleChurch(
            ChurchLists::getChurchesById(Uuid::uuid4()->toString())
        );

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($this->userFiltersDtoMock->churchId));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findUsersByChurchService->execute($this->userFiltersDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findUsersByChurchService = $this->getFindUsersByChurchService();

        $findUsersByChurchService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findUsersByChurchService->execute($this->userFiltersDtoMock);
    }
}
