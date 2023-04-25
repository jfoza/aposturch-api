<?php

namespace Tests\Unit\App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Responses\CountAdminUsersResponse;
use App\Features\Users\AdminUsers\Services\ShowCountAdminUsersByProfile;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Infra\Repositories\ProfilesRepository;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowCountAdminUsersByProfileTest extends TestCase
{
    private MockObject|ProfilesRepositoryInterface $profilesRepositoryMock;
    private MockObject|CountAdminUsersResponse $countAdminUsersResponseMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profilesRepositoryMock = $this->createMock(ProfilesRepository::class);
        $this->countAdminUsersResponseMock = $this->createMock(CountAdminUsersResponse::class);
    }

    public function getShowCountAdminUsersByProfile(): ShowCountAdminUsersByProfile
    {
        return new ShowCountAdminUsersByProfile(
            $this->profilesRepositoryMock,
            $this->countAdminUsersResponseMock
        );
    }

    public function dataProviderCount(): array
    {
        return [
            'By Admin Master Rule' => [RulesEnum::COUNT_USERS_ADMIN_MASTER_VIEW->value],
            'By Admin Church Rule' => [RulesEnum::COUNT_USERS_ADMIN_CHURCH_VIEW->value],
            'By Admin Module Rule' => [RulesEnum::COUNT_USERS_ADMIN_MODULE_VIEW->value],
            'By Assistant Rule'    => [RulesEnum::COUNT_USERS_ASSISTANT_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderCount
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_count_users_in_profile(string $rule): void
    {
        $showCountAdminUsersByProfile = $this->getShowCountAdminUsersByProfile();

        $showCountAdminUsersByProfile->setPolicy(new Policy([$rule]));

        $this
            ->profilesRepositoryMock
            ->method('findCountUsersByProfile')
            ->willReturn(50);

        $counts = $showCountAdminUsersByProfile->execute();

        $this->assertInstanceOf(CountAdminUsersResponse::class, $counts);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $showCountAdminUsersByProfile = $this->getShowCountAdminUsersByProfile();

        $showCountAdminUsersByProfile->setPolicy(new Policy(['ABC']));

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $showCountAdminUsersByProfile->execute();
    }
}
