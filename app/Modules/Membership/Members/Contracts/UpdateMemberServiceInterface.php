<?php

namespace App\Modules\Membership\Members\Contracts;

use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;

interface UpdateMemberServiceInterface
{
    public function execute(UserDTO $userDTO): UpdateMemberResponse;
}
