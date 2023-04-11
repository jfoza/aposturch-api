<?php

namespace App\Features\ZipCode\Services;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AddressByZipCodeService
{
    /**
     * @throws AppException
     */
    public function execute(string $zipCode)
    {
        $response = json_decode(Http::get("https://viacep.com.br/ws/{$zipCode}/json"));

        if(is_null($response) || isset($response->erro)) {
            $this->dispatchExceptionNotFound();
        }

        return $response;
    }

    /**
     * @throws AppException
     */
    private function dispatchExceptionNotFound()
    {
        throw new AppException(
            MessagesEnum::ADDRESS_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    }
}
