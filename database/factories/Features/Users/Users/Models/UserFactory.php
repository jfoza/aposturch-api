<?php

namespace Database\Factories\Features\Users\Users\Models;

use App\Features\Users\Users\Models\User;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Utils\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $name = RandomStringHelper::alnumGenerate();

        return [
            User::NAME      => $name,
            User::EMAIL     => $name.'@email.com',
            User::PASSWORD  => Hash::generateHash('pass'),
            User::ACTIVE    => true,
        ];
    }
}
