<?php

namespace App\Features\General\UniqueCodePrefixes\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\CreateUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesDTO;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateUniqueCodePrefixService extends AuthenticatedService implements CreateUniqueCodePrefixServiceInterface
{
    public function __construct(
        private readonly UniqueCodePrefixesRepositoryInterface $uniqueCodePrefixesRepository
    ) {}

    /**
     * @param UniqueCodePrefixesDTO $uniqueCodePrefixesDTO
     * @return object
     * @throws AppException
     */
    public function execute(UniqueCodePrefixesDTO $uniqueCodePrefixesDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::UNIQUE_CODE_PREFIXES_INSERT->value);

        Transaction::beginTransaction();

        try
        {
            $created = $this->uniqueCodePrefixesRepository->create($uniqueCodePrefixesDTO);

            Transaction::commit();

            return $created;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
