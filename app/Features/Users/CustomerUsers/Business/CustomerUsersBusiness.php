<?php

namespace App\Features\Users\CustomerUsers\Business;

use App\Shared\Enums\RulesEnum;
use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersBusinessInterface;
use App\Features\Users\CustomerUsers\DTO\CustomerUsersFiltersDTO;
use App\Features\Users\CustomerUsers\Http\Responses\CustomerUserResponse;
use App\Features\Users\CustomerUsers\Jobs\EmailGeneratedCustomerUserJob;
use App\Features\Users\CustomerUsers\Services\CreateCustomerService;
use App\Features\Users\CustomerUsers\Services\ListCustomersService;
use App\Features\Users\CustomerUsers\Services\ShowCustomerService;
use App\Features\Users\CustomerUsers\Services\UpdateCustomerService;
use App\Features\Users\Users\DTO\UserDTO;

class CustomerUsersBusiness extends Business implements CustomerUsersBusinessInterface
{
    public function __construct(
        private readonly ListCustomersService $listCustomersService,
        private readonly ShowCustomerService $showCustomerService,
        private readonly CreateCustomerService $createCustomerService,
        private readonly UpdateCustomerService $updateCustomerService,
    ) {}

    /**
     * @throws AppException
     */
    public function findAll(CustomerUsersFiltersDTO $customerUsersFiltersDTO)
    {
        $this->getPolicy()->havePermission(RulesEnum::CUSTOMERS_VIEW->value);

        return $this->listCustomersService->execute($customerUsersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    public function findById(string $customerUserId)
    {
        $this->getPolicy()->havePermission(RulesEnum::CUSTOMERS_VIEW->value);

        return $this->showCustomerService->execute($customerUserId);
    }

    /**
     * @throws AppException
     */
    public function create(UserDTO $userDTO): CustomerUserResponse
    {
        $this->getPolicy()->havePermission(RulesEnum::CUSTOMERS_INSERT->value);

        $userDTO->customerUsersDTO->verifiedEmail = true;

        $created = $this->createCustomerService->execute($userDTO);

        EmailGeneratedCustomerUserJob::dispatch($userDTO->newPasswordGenerationsDTO);

        return $created;
    }

    /**
     * @throws AppException
     */
    public function save(UserDTO $userDTO): CustomerUserResponse
    {
        $this->getPolicy()->havePermission(RulesEnum::CUSTOMERS_UPDATE->value);

        return $this->updateCustomerService->execute($userDTO);
    }
}
