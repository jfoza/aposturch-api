<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\FindAllChurchesByUserLoggedServiceInterface;
use App\Modules\Membership\Church\DTO\ChurchFiltersDTO;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllChurchesByUserLoggedService extends AuthenticatedService implements FindAllChurchesByUserLoggedServiceInterface
{
    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly ChurchFiltersDTO $churchFiltersDTO,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(): LengthAwarePaginator|Collection
    {
        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_ADMIN_MASTER_VIEW->value)
                => $this->findAllByAdminMaster(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_VIEW->value)
                => $this->findAllByGeneral(),

            default => $policy->dispatchForbiddenError()
        };
    }

    private function findAllByAdminMaster(): LengthAwarePaginator|Collection
    {
        return $this->churchRepository->findAll($this->churchFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findAllByGeneral(): LengthAwarePaginator|Collection
    {
        $this->churchFiltersDTO->active = true;

        $this->churchFiltersDTO->churchIds = $this->getUserMemberChurchesId();

        return $this->churchRepository->findAll($this->churchFiltersDTO);
    }
}
