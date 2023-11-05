<?php

namespace Tests\Unit\App\Features\Users\Sessions\Services;

use App\Features\Auth\DTO\AuthDTO;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\Models\Session;
use App\Features\Users\Sessions\Repositories\SessionsRepository;
use App\Features\Users\Sessions\Services\CreateSessionDataService;
use App\Shared\Enums\AuthTypesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Libraries\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class CreateSessionDataServiceTest extends TestCase
{
    private MockObject|SessionsRepositoryInterface $sessionsRepositoryMock;
    private MockObject|AuthDTO $authDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sessionsRepositoryMock = $this->createMock(SessionsRepository::class);

        $this->authDtoMock = $this->createMock(AuthDTO::class);

        $this->authDtoMock->userId      = Uuid::uuid4Generate();
        $this->authDtoMock->initialDate = Helpers::getCurrentTimestampCarbon();
        $this->authDtoMock->finalDate   = Helpers::getCurrentTimestampCarbon()->addDays(2);
        $this->authDtoMock->token       = hash('sha256', Uuid::uuid4Generate());;
        $this->authDtoMock->ipAddress   = '192.168.1.5';
        $this->authDtoMock->authType    = AuthTypesEnum::EMAIL_PASSWORD->value;
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
                Session::ID => Uuid::uuid4Generate(),
            ]));

        $created = $createSessionDataService->execute($this->authDtoMock);

        $this->assertInstanceOf(Session::class, $created);
    }
}
