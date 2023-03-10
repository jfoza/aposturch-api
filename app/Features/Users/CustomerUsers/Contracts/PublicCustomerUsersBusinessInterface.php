<?php

namespace App\Features\Users\CustomerUsers\Contracts;

use App\Features\Users\CustomerUsers\Http\Responses\CustomerUserResponse;
use App\Features\Users\Users\DTO\PasswordDTO;
use App\Features\Users\Users\DTO\UserDTO;

interface PublicCustomerUsersBusinessInterface
{
    public function findById();
    public function verifyEmailExists(string $email);
    public function create(UserDTO $userDTO): CustomerUserResponse;
    public function save(UserDTO $userDTO): CustomerUserResponse;
    public function resendEmailNewCustomerUser(string $email);
    public function authorizeCustomerUser(string $userId, string $code);
    public function saveNewPassword(PasswordDTO $passwordDTO);
}
