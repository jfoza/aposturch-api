<?php

namespace Tests\Unit\App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\DTO\ChurchFiltersDTO;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\FindAllChurchesService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ChurchLists;

class FindAllChurchesServiceTest extends TestCase
{
    private MockObject|ChurchRepositoryInterface $churchRepositoryMock;
    private MockObject|ChurchFiltersDTO $churchFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->churchRepositoryMock = $this->createMock(ChurchRepository::class);
        $this->churchFiltersDtoMock = $this->createMock(ChurchFiltersDTO::class);
    }

    public function getFindAllChurchesService(): FindAllChurchesService
    {
        return new FindAllChurchesService(
            $this->churchRepositoryMock
        );
    }

    public function dataProviderFindAllChurches(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_VIEW->value],
            'By Admin Church Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_VIEW->value],
            'By Admin Module Rule' => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_VIEW->value],
            'By Assistant Rule'    => [RulesEnum::MEMBERSHIP_MODULE_CHURCH_ASSISTANT_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderFindAllChurches
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_to_return_churches_list(string $rule): void
    {
        $findAllChurchesService = $this->getFindAllChurchesService();

        $findAllChurchesService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->churchRepositoryMock
            ->method('findAll')
            ->willReturn(ChurchLists::getChurches());

        $churches = $findAllChurchesService->execute($this->churchFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $churches);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllChurchesService = $this->getFindAllChurchesService();

        $findAllChurchesService->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllChurchesService->execute($this->churchFiltersDtoMock);
    }
}
