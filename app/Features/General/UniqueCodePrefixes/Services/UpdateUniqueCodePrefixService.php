<?php

namespace App\Features\General\UniqueCodePrefixes\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\UpdateUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Symfony\Component\HttpFoundation\Response;

class UpdateUniqueCodePrefixService extends AuthenticatedService implements UpdateUniqueCodePrefixServiceInterface
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
        $this->getPolicy()->havePermission(RulesEnum::UNIQUE_CODE_PREFIXES_UPDATE->value);

        if(!$this->uniqueCodePrefixesRepository->findById($uniqueCodePrefixesDTO->id))
        {
            throw new AppException(
                MessagesEnum::UNIQUE_CODE_PREFIX_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        Transaction::beginTransaction();

        try
        {
            $updated = $this->uniqueCodePrefixesRepository->save($uniqueCodePrefixesDTO);

            Transaction::commit();

            return $updated;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
