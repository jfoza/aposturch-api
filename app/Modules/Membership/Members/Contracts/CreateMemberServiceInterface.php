<?php

namespace App\Modules\Membership\Members\Contracts;

use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Members\Responses\InsertMemberResponse;

interface CreateMemberServiceInterface
{
    public function execute(UserDTO $userDTO): InsertMemberResponse;
}
