<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\FindAllChurchesServiceInterface;
use App\Modules\Members\Church\DTO\ChurchFiltersDTO;
use App\Shared\Enums\RulesEnum;

class FindAllChurchesService extends Service implements FindAllChurchesServiceInterface
{
    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ChurchFiltersDTO $churchFiltersDTO)
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_VIEW->value);

        return $this->churchRepository->findAll($churchFiltersDTO);
    }
}
