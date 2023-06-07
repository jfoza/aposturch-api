<?php

namespace Tests\Unit\App\Features\Users\Profiles\Services;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Features\Users\Profiles\Services\FindAllProfilesInListMembersAuthenticatedService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ProfilesLists;

class FindAllProfilesInListMembersServiceTest extends TestCase
{
    private MockObject|ProfilesRepositoryInterface $profilesRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profilesRepositoryMock = $this->createMock(ProfilesRepository::class);
    }

    public function getFindAllProfilesInListMembersService(): FindAllProfilesInListMembersAuthenticatedService
    {
        return new FindAllProfilesInListMembersAuthenticatedService(
            $this->profilesRepositoryMock
        );
    }

    public function test_should_return_profiles_by_user_ability(): void
    {
        $findAllProfilesInListMembersService = $this->getFindAllProfilesInListMembersService();

        $findAllProfilesInListMembersService->setPolicy(
            new Policy([
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_PROFILES_FILTER_VIEW->value
            ])
        );

        $this
            ->profilesRepositoryMock
            ->method('findAllByUniqueName')
            ->willReturn(ProfilesLists::getAllProfiles());

        $profiles = $findAllProfilesInListMembersService->execute();

        $this->assertInstanceOf(Collection::class, $profiles);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllProfilesInListMembersService = $this->getFindAllProfilesInListMembersService();

        $findAllProfilesInListMembersService->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllProfilesInListMembersService->execute();
    }
}
