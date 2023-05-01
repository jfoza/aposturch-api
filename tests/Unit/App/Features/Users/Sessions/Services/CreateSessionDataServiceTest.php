<?php

namespace Tests\Unit\App\Features\Users\Sessions\Services;

use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\DTO\SessionDTO;
use App\Features\Users\Sessions\Models\Session;
use App\Features\Users\Sessions\Repositories\SessionsRepository;
use App\Features\Users\Sessions\Services\CreateSessionDataService;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CreateSessionDataServiceTest extends TestCase
{
    private MockObject|SessionsRepositoryInterface $sessionsRepositoryMock;
    private MockObject|SessionDTO $sessionDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sessionsRepositoryMock = $this->createMock(SessionsRepository::class);

        $this->sessionDtoMock = $this->createMock(SessionDTO::class);
    }

    public function getCreateSessionDataService(): CreateSessionDataService
    {
        return new CreateSessionDataService($this->sessionsRepositoryMock);
    }

    public function test_should_insert_authentication_data()
    {
        $createSessionDataService = $this->getCreateSessionDataService();

        $this
            ->sessionsRepositoryMock
            ->method('create')
            ->willReturn(Session::make([
                Session::ID => Uuid::uuid4()->toString(),
            ]));

        $created = $createSessionDataService->execute($this->sessionDtoMock);

        $this->assertInstanceOf(Session::class, $created);
    }
}
