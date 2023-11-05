<?php

namespace App\Features\General\UniqueCodePrefixes\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\FindByUniqueCodePrefixIdServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Symfony\Component\HttpFoundation\Response;

class FindByUniqueCodePrefixIdService extends AuthenticatedService implements FindByUniqueCodePrefixIdServiceInterface
{
    public function __construct(
        private readonly UniqueCodePrefixesRepositoryInterface $uniqueCodePrefixesRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::UNIQUE_CODE_PREFIXES_VIEW->value);

        if(!$uniqueCodePrefix = $this->uniqueCodePrefixesRepository->findById($id))
        {
            throw new AppException(
                MessagesEnum::UNIQUE_CODE_PREFIX_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $uniqueCodePrefix;
    }
}
