<?php

namespace Tests\Unit\App\Features\General\UniqueCodePrefixes\Services;

use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\Models\UniqueCodePrefix;
use App\Features\General\UniqueCodePrefixes\Repositories\UniqueCodePrefixesRepository;
use App\Features\General\UniqueCodePrefixes\Services\RemoveUniqueCodePrefixService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RemoveUniqueCodePrefixServiceTest extends TestCase
{
    private MockObject|UniqueCodePrefixesRepositoryInterface $uniqueCodePrefixesRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uniqueCodePrefixesRepositoryMock = $this->createMock(UniqueCodePrefixesRepository::class);
    }

    public function getRemoveUniqueCodePrefixService(): RemoveUniqueCodePrefixService
    {
        return new RemoveUniqueCodePrefixService(
            $this->uniqueCodePrefixesRepositoryMock,
        );
    }

    public function test_should_return_unique_code_prefixes_list()
    {
        $removeUniqueCodePrefixService = $this->getRemoveUniqueCodePrefixService();

        $removeUniqueCodePrefixService->setPolicy(
            new Policy([RulesEnum::UNIQUE_CODE_PREFIXES_DELETE->value])
        );

        $this
            ->uniqueCodePrefixesRepositoryMock
            ->method('findById')
            ->willReturn((object) ([UniqueCodePrefix::ID => Uuid::uuid4Generate()]));

        $removeUniqueCodePrefixService->execute(Uuid::uuid4Generate());

        $this->assertTrue(true);
    }

    public function test_should_return_error_if_unique_code_prefix_not_exists()
    {
        $removeUniqueCodePrefixService = $this->getRemoveUniqueCodePrefixService();

        $removeUniqueCodePrefixService->setPolicy(
            new Policy([RulesEnum::UNIQUE_CODE_PREFIXES_DELETE->value])
        );

        $this
            ->uniqueCodePrefixesRepositoryMock
            ->method('findById')
            ->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->expectExceptionMessage(json_encode(MessagesEnum::UNIQUE_CODE_PREFIX_NOT_FOUND));

        $removeUniqueCodePrefixService->execute(Uuid::uuid4Generate());
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $removeUniqueCodePrefixService = $this->getRemoveUniqueCodePrefixService();

        $removeUniqueCodePrefixService->setPolicy(
            new Policy([
                'ABC'
            ])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $removeUniqueCodePrefixService->execute(Uuid::uuid4Generate());
    }
}
