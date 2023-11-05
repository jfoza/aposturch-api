<?php

namespace Tests\Unit\App\Features\General\UniqueCodePrefixes\Services;

use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesDTO;
use App\Features\General\UniqueCodePrefixes\Models\UniqueCodePrefix;
use App\Features\General\UniqueCodePrefixes\Repositories\UniqueCodePrefixesRepository;
use App\Features\General\UniqueCodePrefixes\Services\UpdateUniqueCodePrefixService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateUniqueCodePrefixServiceTest extends TestCase
{
    private MockObject|UniqueCodePrefixesRepositoryInterface $uniqueCodePrefixesRepositoryMock;
    private MockObject|UniqueCodePrefixesDTO $uniqueCodePrefixesDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uniqueCodePrefixesRepositoryMock = $this->createMock(UniqueCodePrefixesRepository::class);
        $this->uniqueCodePrefixesDtoMock        = $this->createMock(UniqueCodePrefixesDTO::class);
    }

    public function getUpdateUniqueCodePrefixService(): UpdateUniqueCodePrefixService
    {
        return new UpdateUniqueCodePrefixService(
            $this->uniqueCodePrefixesRepositoryMock,
        );
    }

    public function test_should_update_unique_code_prefix()
    {
        $updateUniqueCodePrefixService = $this->getUpdateUniqueCodePrefixService();

        $updateUniqueCodePrefixService->setPolicy(
            new Policy([RulesEnum::UNIQUE_CODE_PREFIXES_UPDATE->value])
        );

        $this->uniqueCodePrefixesDtoMock->id     = Uuid::uuid4Generate();
        $this->uniqueCodePrefixesDtoMock->prefix = 'AL';
        $this->uniqueCodePrefixesDtoMock->active = true;

        $this
            ->uniqueCodePrefixesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([UniqueCodePrefix::ID => Uuid::uuid4Generate()]));

        $updated = $updateUniqueCodePrefixService->execute($this->uniqueCodePrefixesDtoMock);

        $this->assertIsObject($updated);
    }

    public function test_should_return_error_if_unique_code_prefix_not_exists()
    {
        $updateUniqueCodePrefixService = $this->getUpdateUniqueCodePrefixService();

        $updateUniqueCodePrefixService->setPolicy(
            new Policy([RulesEnum::UNIQUE_CODE_PREFIXES_UPDATE->value])
        );

        $this->uniqueCodePrefixesDtoMock->id     = Uuid::uuid4Generate();
        $this->uniqueCodePrefixesDtoMock->prefix = 'AL';
        $this->uniqueCodePrefixesDtoMock->active = true;

        $this
            ->uniqueCodePrefixesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::UNIQUE_CODE_PREFIX_NOT_FOUND));

        $updateUniqueCodePrefixService->execute($this->uniqueCodePrefixesDtoMock);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $updateUniqueCodePrefixService = $this->getUpdateUniqueCodePrefixService();

        $updateUniqueCodePrefixService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $updateUniqueCodePrefixService->execute($this->uniqueCodePrefixesDtoMock);
    }
}
