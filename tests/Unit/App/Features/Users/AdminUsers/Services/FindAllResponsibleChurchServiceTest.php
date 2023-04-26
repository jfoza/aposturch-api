<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\FindAllResponsibleChurchService;
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

class FindAllResponsibleChurchServiceTest extends TestCase
{
    private MockObject|AdminUsersRepositoryInterface $adminUsersRepositoryMock;
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUsersRepositoryMock = $this->createMock(AdminUsersRepository::class);
        $this->churchRepositoryMock     = $this->createMock(ChurchRepository::class);
    }

    public function dataProviderFindAll(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_VIEW->value],
            'By Admin Church Rule' => [RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_VIEW->value],
        ];
    }

    public function getFindAllResponsibleChurchService(): FindAllResponsibleChurchService
    {
        return new FindAllResponsibleChurchService(
            $this->adminUsersRepositoryMock,
            $this->churchRepositoryMock,
        );
    }

    /**
     * @dataProvider dataProviderFindAll
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_to_return_responsible_church_list(string $rule): void
    {
        $findAllResponsibleChurchService = $this->getFindAllResponsibleChurchService();

        $findAllResponsibleChurchService->setPolicy(new Policy([$rule]));

        $churchId = Uuid::uuid4()->toString();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(ChurchLists::showChurch($churchId));

        $this
            ->adminUsersRepositoryMock
            ->method('findAllResponsibleChurch')
            ->willReturn(ChurchLists::getChurches());

        $adminUsers = $findAllResponsibleChurchService->execute($churchId);

        $this->assertInstanceOf(Collection::class, $adminUsers);
    }

    public function test_should_return_exception_if_church_id_not_exists()
    {
        $findAllResponsibleChurchService = $this->getFindAllResponsibleChurchService();

        $findAllResponsibleChurchService->setPolicy(new Policy([
            RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_VIEW->value
        ]));

        $churchId = Uuid::uuid4()->toString();

        $this
            ->churchRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);

        $findAllResponsibleChurchService->execute($churchId);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllResponsibleChurchService = $this->getFindAllResponsibleChurchService();

        $findAllResponsibleChurchService->setPolicy(new Policy(['ABC']));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllResponsibleChurchService->execute(Uuid::uuid4()->toString());
    }
}
