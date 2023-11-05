<?php

namespace App\Features\General\UniqueCodePrefixes\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\FindAllUniqueCodePrefixesServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllUniqueCodePrefixesService extends AuthenticatedService implements FindAllUniqueCodePrefixesServiceInterface
{
    public function __construct(
        private readonly UniqueCodePrefixesRepositoryInterface $uniqueCodePrefixesRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(UniqueCodePrefixesFiltersDTO $uniqueCodePrefixesFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::UNIQUE_CODE_PREFIXES_VIEW->value);

        return $this->uniqueCodePrefixesRepository->findAll($uniqueCodePrefixesFiltersDTO);
    }
}
