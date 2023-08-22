<?php

namespace App\Modules\Membership\Members\Controllers;

use App\Modules\Membership\Members\Contracts\Updates\AddressDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\ChurchDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\GeneralDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\ModulesDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\PasswordDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\ProfileDataUpdateServiceInterface;
use App\Modules\Membership\Members\DTO\AddressDataUpdateDTO;
use App\Modules\Membership\Members\DTO\GeneralDataUpdateDTO;
use App\Modules\Membership\Members\Requests\Updates\AddressDataUpdateRequest;
use App\Modules\Membership\Members\Requests\Updates\ChurchDataUpdateRequest;
use App\Modules\Membership\Members\Requests\Updates\GeneralDataUpdateRequest;
use App\Modules\Membership\Members\Requests\Updates\ModulesDataUpdateRequest;
use App\Modules\Membership\Members\Requests\Updates\PasswordDataUpdateRequest;
use App\Modules\Membership\Members\Requests\Updates\ProfileDataUpdateRequest;
use App\Shared\Helpers\Helpers;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class UpdateMembersController
{
    public function __construct(
        private GeneralDataUpdateServiceInterface  $generalDataUpdateService,
        private AddressDataUpdateServiceInterface  $addressDataUpdateService,
        private ChurchDataUpdateServiceInterface   $churchDataUpdateService,
        private ModulesDataUpdateServiceInterface  $modulesDataUpdateService,
        private ProfileDataUpdateServiceInterface  $profileDataUpdateService,
        private PasswordDataUpdateServiceInterface $passwordDataUpdateService,
    ) {}

    public function updateGeneralData(
        GeneralDataUpdateRequest $generalDataUpdateRequest,
        GeneralDataUpdateDTO $generalDataUpdateDTO
    ): JsonResponse
    {
        $generalDataUpdateDTO->id     = $generalDataUpdateRequest->id;
        $generalDataUpdateDTO->name   = $generalDataUpdateRequest->name;
        $generalDataUpdateDTO->email  = $generalDataUpdateRequest->email;
        $generalDataUpdateDTO->active = $generalDataUpdateRequest->active;
        $generalDataUpdateDTO->phone  = Helpers::onlyNumbers($generalDataUpdateRequest->phone);

        $updated = $this->generalDataUpdateService->execute($generalDataUpdateDTO);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updateAddressData(
        AddressDataUpdateRequest $addressDataUpdateRequest,
        AddressDataUpdateDTO $addressDataUpdateDTO
    ): JsonResponse
    {
        $addressDataUpdateDTO->id            = $addressDataUpdateRequest->id;
        $addressDataUpdateDTO->zipCode       = Helpers::onlyNumbers($addressDataUpdateRequest->zipCode);
        $addressDataUpdateDTO->address       = $addressDataUpdateRequest->address;
        $addressDataUpdateDTO->numberAddress = $addressDataUpdateRequest->numberAddress;
        $addressDataUpdateDTO->complement    = $addressDataUpdateRequest->complement;
        $addressDataUpdateDTO->district      = $addressDataUpdateRequest->district;
        $addressDataUpdateDTO->cityId        = $addressDataUpdateRequest->cityId;
        $addressDataUpdateDTO->uf            = $addressDataUpdateRequest->uf;

        $updated = $this->addressDataUpdateService->execute($addressDataUpdateDTO);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updateChurchData(
        ChurchDataUpdateRequest $churchDataUpdateRequest,
    ): JsonResponse
    {
        $userId   = $churchDataUpdateRequest->id;
        $churchId = $churchDataUpdateRequest->churchId;

        $updated = $this->churchDataUpdateService->execute($userId, $churchId);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updateModulesData(
        ModulesDataUpdateRequest $modulesDataUpdateRequest,
    ): JsonResponse
    {
        $userId    = $modulesDataUpdateRequest->id;
        $modulesId = $modulesDataUpdateRequest->modulesId;

        $updated = $this->modulesDataUpdateService->execute($userId, $modulesId);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updateProfileData(
        ProfileDataUpdateRequest $profileDataUpdateRequest,
    ): JsonResponse
    {
        $userId    = $profileDataUpdateRequest->id;
        $profileId = $profileDataUpdateRequest->profileId;

        $updated = $this->profileDataUpdateService->execute($userId, $profileId);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updatePasswordData(
        PasswordDataUpdateRequest $passwordDataUpdateRequest,
    ): JsonResponse
    {
        $userId   = $passwordDataUpdateRequest->id;
        $password = $passwordDataUpdateRequest->password;

        $updated = $this->passwordDataUpdateService->execute($userId, $password);

        return response()->json($updated, Response::HTTP_OK);
    }
}
