<?php
//
//namespace Tests\Unit\App\Features\Users\Users\Services;
//
//use App\Exceptions\AppException;
//use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
//use App\Features\Users\Users\DTO\UserFiltersDTO;
//use App\Features\Users\Users\Infra\Repositories\UsersRepository;
//use App\Features\Users\Users\Services\FindUsersByChurchService;
//use App\Shared\ACL\Policy;
//use App\Shared\Enums\RulesEnum;
//use Illuminate\Support\Collection;
//use PHPUnit\Framework\MockObject\MockObject;
//use Symfony\Component\HttpFoundation\Response;
//use Tests\TestCase;
//use Tests\Unit\App\Resources\UsersLists;
//
//class FindAllUsersServiceTest extends TestCase
//{
//    private MockObject|UsersRepositoryInterface $usersRepositoryMock;
//    private MockObject|UserFiltersDTO $userFiltersDtoMock;
//
//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        $this->usersRepositoryMock = $this->createMock(UsersRepository::class);
//        $this->userFiltersDtoMock  = $this->createMock(UserFiltersDTO::class);
//    }
//
//    public function getFindAllUsersService(): FindUsersByChurchService
//    {
//        return new FindUsersByChurchService(
//            $this->usersRepositoryMock
//        );
//    }
//
//    public function test_should_return_users_list()
//    {
//        $findAllUsersService = $this->getFindAllUsersService();
//
//        $findAllUsersService->setPolicy(
//            new Policy([
//                RulesEnum::USERS_VIEW->value
//            ])
//        );
//
//        $this
//            ->usersRepositoryMock
//            ->method('findAll')
//            ->willReturn(UsersLists::findAllUsers());
//
//        $users = $findAllUsersService->execute($this->userFiltersDtoMock);
//
//        $this->assertInstanceOf(Collection::class, $users);
//    }
//
//    public function test_should_return_empty()
//    {
//        $findAllUsersService = $this->getFindAllUsersService();
//
//        $findAllUsersService->setPolicy(
//            new Policy([
//                RulesEnum::USERS_VIEW->value
//            ])
//        );
//
//        $this
//            ->usersRepositoryMock
//            ->method('findAll')
//            ->willReturn(Collection::empty());
//
//        $users = $findAllUsersService->execute($this->userFiltersDtoMock);
//
//        $this->assertInstanceOf(Collection::class, $users);
//    }
//
//    public function test_should_return_exception_if_user_is_not_authorized()
//    {
//        $findAllUsersService = $this->getFindAllUsersService();
//
//        $findAllUsersService->setPolicy(
//            new Policy([
//                'ABC'
//            ])
//        );
//
//        $this->expectException(AppException::class);
//        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);
//
//        $findAllUsersService->execute($this->userFiltersDtoMock);
//    }
//}
