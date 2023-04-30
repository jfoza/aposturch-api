<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\Users\Contracts\FindUsersByChurchServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserFiltersDTO;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindUsersByChurchService extends Service implements FindUsersByChurchServiceInterface
{
    private UserFiltersDTO $userFiltersDTO;

    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(UserFiltersDTO $userFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->userFiltersDTO = $userFiltersDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW->value)
                => $this->findByAdminMaster(),

            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW->value)
                => $this->findByAdminChurch(),

            default  => $policy->dispatchErrorForbidden(),
        };
    }

    /**
     * @throws AppException
     */
    private function findByAdminMaster(): LengthAwarePaginator|Collection
    {
        $this->handleValidations();

        return $this->usersRepository->findAllByChurch($this->userFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findByAdminChurch(): LengthAwarePaginator|Collection
    {
        $this->handleValidations();

        $this->userHasChurch(
            Church::ID,
            $this->userFiltersDTO->churchId
        );

        return $this->usersRepository->findAllByChurch($this->userFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function handleValidations()
    {
        ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->userFiltersDTO->churchId
        );
    }
}
