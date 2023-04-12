<?php

namespace Tests\Unit\App\Modules\Members\Services;

use App\Exceptions\AppException;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\DTO\ChurchFiltersDTO;
use App\Modules\Members\Church\Repositories\ChurchRepository;
use App\Modules\Members\Church\Services\FindAllChurchesService;
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

    public function test_should_return_churches_list()
    {
        $findAllChurchesService = $this->getFindAllChurchesService();

        $findAllChurchesService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_VIEW->value
            ])
        );

        $this
            ->churchRepositoryMock
            ->method('findAll')
            ->willReturn(ChurchLists::getChurches());

        $churches = $findAllChurchesService->execute($this->churchFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $churches);
    }

    public function test_should_return_empty()
    {
        $findAllChurchesService = $this->getFindAllChurchesService();

        $findAllChurchesService->setPolicy(
            new Policy([
                RulesEnum::MEMBERS_MODULE_CHURCH_VIEW->value
            ])
        );

        $this
            ->churchRepositoryMock
            ->method('findAll')
            ->willReturn([]);

        $churches = $findAllChurchesService->execute($this->churchFiltersDtoMock);

        $this->assertEmpty($churches);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllChurchesService = $this->getFindAllChurchesService();

        $findAllChurchesService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllChurchesService->execute($this->churchFiltersDtoMock);
    }
}
