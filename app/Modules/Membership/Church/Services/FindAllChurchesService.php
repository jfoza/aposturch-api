<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\FindAllChurchesServiceInterface;
use App\Modules\Membership\Church\DTO\ChurchFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllChurchesService extends Service implements FindAllChurchesServiceInterface
{
    private ChurchFiltersDTO $churchFiltersDTO;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ChurchFiltersDTO $churchFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->churchFiltersDTO = $churchFiltersDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_VIEW->value) => $this->findByAdminChurch(),
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MODULE_VIEW->value) => $this->findByAdminModule(),
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ASSISTANT_VIEW->value)    => $this->findByAssistant(),

            default  => $policy->dispatchErrorForbidden(),
        };
    }

    private function findByAdminMaster()
    {
        return $this->churchRepository->findAll($this->churchFiltersDTO);
    }

    private function findByAdminChurch()
    {
        return $this->churchRepository->findAll($this->churchFiltersDTO);
    }

    private function findByAdminModule()
    {
        return $this->churchRepository->findAll($this->churchFiltersDTO);
    }

    private function findByAssistant()
    {
        return $this->churchRepository->findAll($this->churchFiltersDTO);
    }
}
