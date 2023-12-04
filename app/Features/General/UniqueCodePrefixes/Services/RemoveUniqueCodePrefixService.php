<?php

namespace App\Features\General\UniqueCodePrefixes\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\RemoveUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Symfony\Component\HttpFoundation\Response;

class RemoveUniqueCodePrefixService extends AuthenticatedService implements RemoveUniqueCodePrefixServiceInterface
{
    public function __construct(
        private readonly UniqueCodePrefixesRepositoryInterface $uniqueCodePrefixesRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): void
    {
        $this->getPolicy()->havePermission(RulesEnum::UNIQUE_CODE_PREFIXES_DELETE->value);

        if(!$this->uniqueCodePrefixesRepository->findById($id))
        {
            throw new AppException(
                MessagesEnum::UNIQUE_CODE_PREFIX_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        Transaction::beginTransaction();

        try
        {
            $this->uniqueCodePrefixesRepository->remove($id);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
