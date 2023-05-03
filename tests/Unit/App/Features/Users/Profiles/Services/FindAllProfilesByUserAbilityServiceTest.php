<?php

namespace Tests\Unit\App\Features\Users\Profiles\Services;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Features\Users\Profiles\Services\FindAllProfilesByUserAbilityService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Unit\App\Resources\ProfilesLists;

class FindAllProfilesByUserAbilityServiceTest extends TestCase
{
    private MockObject|ProfilesRepositoryInterface $profilesRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profilesRepositoryMock = $this->createMock(ProfilesRepository::class);
    }

    public function getFindAllProfilesByUserAbilityService(): FindAllProfilesByUserAbilityService
    {
        return new FindAllProfilesByUserAbilityService(
            $this->profilesRepositoryMock
        );
    }

    public function dataProviderFindAllProfiles(): array
    {
        return [
            'By Support Rule'      => [RulesEnum::PROFILES_SUPPORT_VIEW->value],
            'By Admin Master Rule' => [RulesEnum::PROFILES_ADMIN_MASTER_VIEW->value],
            'By Admin Church Rule' => [RulesEnum::PROFILES_ADMIN_CHURCH_VIEW->value],
            'By Admin Module Rule' => [RulesEnum::PROFILES_ADMIN_MODULE_VIEW->value],
            'By Assistant Rule'    => [RulesEnum::PROFILES_ASSISTANT_VIEW->value],
        ];
    }

    /**
     * @dataProvider dataProviderFindAllProfiles
     *
     * @param string $rule
     * @return void
     * @throws AppException
     */
    public function test_should_return_profiles_by_user_ability(string $rule): void
    {
        $findAllProfilesByUserAbilityService = $this->getFindAllProfilesByUserAbilityService();

        $findAllProfilesByUserAbilityService->setPolicy(
            new Policy([$rule])
        );

        $this
            ->profilesRepositoryMock
            ->method('findAllByUniqueName')
            ->willReturn(ProfilesLists::getAllProfiles());

        $profiles = $findAllProfilesByUserAbilityService->execute();

        $this->assertInstanceOf(Collection::class, $profiles);
    }

    public function test_should_return_exception_if_user_is_not_authorized()
    {
        $findAllProfilesByUserAbilityService = $this->getFindAllProfilesByUserAbilityService();

        $findAllProfilesByUserAbilityService->setPolicy(
            new Policy(['ABC'])
        );

        $this->expectException(AppException::class);
        $this->expectExceptionCode(Response::HTTP_FORBIDDEN);

        $findAllProfilesByUserAbilityService->execute();
    }
}
