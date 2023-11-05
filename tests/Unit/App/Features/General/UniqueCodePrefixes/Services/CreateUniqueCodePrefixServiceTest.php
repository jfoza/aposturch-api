<?php

namespace Tests\Unit\App\Features\General\UniqueCodePrefixes\Services;

use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesDTO;
use App\Features\General\UniqueCodePrefixes\Repositories\UniqueCodePrefixesRepository;
use App\Features\General\UniqueCodePrefixes\Services\CreateUniqueCodePrefixService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateUniqueCodePrefixServiceTest extends TestCase
{
    private MockObject|UniqueCodePrefixesRepositoryInterface $uniqueCodePrefixesRepositoryMock;
    private MockObject|UniqueCodePrefixesDTO $uniqueCodePrefixesDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uniqueCodePrefixesRepositoryMock = $this->createMock(UniqueCodePrefixesRepository::class);
        $this->uniqueCodePrefixesDtoMock        = $this->createMock(UniqueCodePrefixesDTO::class);
    }

    public function getCreateUniqueCodePrefixService(): CreateUniqueCodePrefixService
    {
        return new CreateUniqueCodePrefixService(
            $this->uniqueCodePrefixesRepositoryMock,
        );
    }

    public function test_should_create_new_unique_code_prefix()
    {
        $createUniqueCodePrefixService = $this->getCreateUniqueCodePrefixService();

        $createUniqueCodePrefixService->setPolicy(
            new Policy([RulesEnum::UNIQUE_CODE_PREFIXES_INSERT->value])
        );

        $this->uniqueCodePrefixesDtoMock->prefix = 'AL';
        $this->uniqueCodePrefixesDtoMock->active = true;

        $created = $createUniqueCodePrefixService->execute($this->uniqueCodePrefixesDtoMock);

        $this->assertIsObject($created);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $createUniqueCodePrefixService = $this->getCreateUniqueCodePrefixService();

        $createUniqueCodePrefixService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $createUniqueCodePrefixService->execute($this->uniqueCodePrefixesDtoMock);
    }
}
