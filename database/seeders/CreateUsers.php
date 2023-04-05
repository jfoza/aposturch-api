<?php

namespace Database\Seeders;

use App\Features\Module\Modules\Infra\Models\Module;
use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Features\Users\ModulesUsers\Infra\Models\ModuleUser;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Users\Infra\Models\User;
use App\Features\Users\Users\Services\Utils\HashService;
use App\Shared\Enums\ModulesEnum;
use App\Shared\Utils\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Nonstandard\Uuid;

class CreateUsers extends Seeder
{
    public function run(): void
    {
        $modules = DB::table(Module::tableName())->get()->toArray();
        $profiles = DB::table(Profile::tableName())->get();

        $modules = collect($modules);
        $profiles = collect($profiles);

        $users = [
            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Giuseppe Foza',
                'email' => 'gfozza@hotmail.com',
                'pass' => HashService::generateHash('Teste123'),
                'profile' => $profiles
                    ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER)
                    ->first()
                    ->id,
                'modules' => $modules->pluck(Module::ID)->toArray(),
            ],

            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Otávio Silveira',
                'email' => 'otavio-silveira@hotmail.com',
                'profile' => $profiles
                    ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER)
                    ->first()
                    ->id,
                'modules' => $modules->pluck(Module::ID)->toArray(),
            ],

            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Felipe Dutra Silveira',
                'email' => 'felipe-dutra@hotmail.com',
                'profile' => $profiles
                    ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_CHURCH)
                    ->first()
                    ->id,
                'modules' => $modules->pluck(Module::ID)->toArray(),
            ],

            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Fábio Dutra Silveira',
                'email' => 'fabio-dutra@hotmail.com',
                'profile' => $profiles
                    ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MODULE)
                    ->first()
                    ->id,
                'modules' => $modules
                    ->whereIn(Module::MODULE_UNIQUE_NAME,
                        [
                            ModulesEnum::USERS->value,
                            ModulesEnum::FINANCE->value,
                        ]
                    )
                    ->pluck(Module::ID)
                    ->toArray(),
            ],

            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Manuela Galeazi',
                'email' => 'manu-galeazi@hotmail.com',
                'profile' => $profiles
                    ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ASSISTANT)
                    ->first()
                    ->id,

                'modules' => $modules
                    ->whereIn(Module::MODULE_UNIQUE_NAME,
                        [
                            ModulesEnum::USERS->value,
                            ModulesEnum::FINANCE->value,
                        ]
                    )
                    ->pluck(Module::ID)
                    ->toArray(),

            ],

            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Usuário Membro',
                'email' => 'usuario-membro@hotmail.com',
                'profile' => $profiles
                    ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::MEMBER)
                    ->first()
                    ->id,
                'modules' => $modules
                    ->whereIn(Module::MODULE_UNIQUE_NAME,
                        [
                            ModulesEnum::SCHEDULE->value,
                            ModulesEnum::STORE->value,
                        ]
                    )
                    ->pluck(Module::ID)
                    ->toArray(),
            ],
        ];

        Transaction::beginTransaction();

        $pass = HashService::generateHash('Teste123');

        try
        {
            foreach ($users as $user)
            {
                User::create([
                    User::ID       => $user['uuid'],
                    User::NAME     => $user['name'],
                    User::EMAIL    => $user['email'],
                    User::PASSWORD => $pass,
                    User::ACTIVE   => true,
                ]);

                AdminUser::create([
                    AdminUser::USER_ID => $user['uuid']
                ]);

                ProfileUser::create([
                    ProfileUser::USER_ID => $user['uuid'],
                    ProfileUser::PROFILE_ID => $user['profile'],
                ]);

                foreach ($user['modules'] as $module)
                {
                    ModuleUser::insert([
                        ModuleUser::USER_ID => $user['uuid'],
                        ModuleUser::MODULE_ID => $module,
                    ]);
                }
            }

            Transaction::commit();
        }

        catch (\Exception)
        {
            Transaction::rollback();
        }
    }
}
