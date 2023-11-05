<?php

namespace App\Features\General\UniqueCodePrefixes\Business;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodeGeneratorBusinessInterface;
use App\Modules\Store\Products\Contracts\ProductUniqueCodeGeneratorServiceInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\UniqueCodeTypesEnum;
use Symfony\Component\HttpFoundation\Response;

class UniqueCodeGeneratorBusiness extends AuthenticatedService implements UniqueCodeGeneratorBusinessInterface
{
    public function __construct(
        private readonly ProductUniqueCodeGeneratorServiceInterface $productUniqueCodeGeneratorService,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(string $uniqueCodeType): array
    {
        return match ($uniqueCodeType)
        {
            UniqueCodeTypesEnum::PRODUCTS->value => $this->productUniqueCodeGeneratorService->execute(),

            default => $this->dispatchErrorInvalidCodeType()
        };
    }

    /**
     * @throws AppException
     */
    private function dispatchErrorInvalidCodeType()
    {
        throw new AppException(
            MessagesEnum::INVALID_CODE_TYPE,
            Response::HTTP_BAD_REQUEST
        );
    }
}
