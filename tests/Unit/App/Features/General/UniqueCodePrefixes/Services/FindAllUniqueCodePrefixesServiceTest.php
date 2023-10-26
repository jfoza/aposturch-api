<?php

namespace Tests\Unit\App\Features\General\UniqueCodePrefixes\Services;

use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesFiltersDTO;
use App\Features\General\UniqueCodePrefixes\Models\UniqueCodePrefix;
use App\Features\General\UniqueCodePrefixes\Repositories\UniqueCodePrefixesRepository;
use App\Features\General\UniqueCodePrefixes\Services\FindAllUniqueCodePrefixesService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FindAllUniqueCodePrefixesServiceTest extends TestCase
{
    private MockObject|UniqueCodePrefixesRepositoryInterface $uniqueCodePrefixesRepositoryMock;
    private MockObject|UniqueCodePrefixesFiltersDTO $uniqueCodePrefixesFiltersDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uniqueCodePrefixesRepositoryMock = $this->createMock(UniqueCodePrefixesRepository::class);
        $this->uniqueCodePrefixesFiltersDtoMock = $this->createMock(UniqueCodePrefixesFiltersDTO::class);
    }

    public function getFindAllUniqueCodePrefixesService(): FindAllUniqueCodePrefixesService
    {
        return new FindAllUniqueCodePrefixesService(
            $this->uniqueCodePrefixesRepositoryMock,
        );
    }

    public function test_should_return_unique_code_prefixes_list()
    {
        $findAllUniqueCodePrefixesService = $this->getFindAllUniqueCodePrefixesService();

        $findAllUniqueCodePrefixesService->setPolicy(
            new Policy([RulesEnum::UNIQUE_CODE_PREFIXES_VIEW->value])
        );

        $this
            ->uniqueCodePrefixesRepositoryMock
            ->method('findAll')
            ->willReturn(
              Collection::make([
                  [UniqueCodePrefix::ID => Uuid::uuid4Generate()]
              ])
            );

        $uniqueCodePrefixes = $findAllUniqueCodePrefixesService->execute($this->uniqueCodePrefixesFiltersDtoMock);

        $this->assertInstanceOf(Collection::class, $uniqueCodePrefixes);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllUniqueCodePrefixesService = $this->getFindAllUniqueCodePrefixesService();

        $findAllUniqueCodePrefixesService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllUniqueCodePrefixesService->execute($this->uniqueCodePrefixesFiltersDtoMock);
    }
}
