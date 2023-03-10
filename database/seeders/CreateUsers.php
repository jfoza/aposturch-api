<?php

namespace Database\Seeders;

use App\Features\Module\Modules\Infra\Models\Module;
use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Features\Users\ModulesUsers\Infra\Models\ModuleRule;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Users\Infra\Models\User;
use App\Features\Users\Users\Services\Utils\HashService;
use App\Shared\Utils\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Nonstandard\Uuid;

class CreateUsers extends Seeder
{
    public function run(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $name = 'Giuseppe Foza';
        $email = 'gfozza@hotmail.com';
        $pass = HashService::generateHash('Teste123');

        Transaction::beginTransaction();

        try
        {
            User::create([
                User::ID => $uuid,
                User::NAME => $name,
                User::EMAIL => $email,
                User::PASSWORD => $pass,
                User::ACTIVE => true,
            ]);

            AdminUser::create([
                AdminUser::USER_ID => $uuid
            ]);

            $profile = DB::table(Profile::tableName())
                ->where(Profile::UNIQUE_NAME, 'admin-master')
                ->first();

            ProfileUser::create([
                ProfileUser::USER_ID => $uuid,
                ProfileUser::PROFILE_ID => $profile->id,
            ]);

            $modules = DB::table(Module::tableName())->get()->toArray();

            foreach ($modules as $module)
            {
                ModuleRule::create([
                    ModuleRule::USER_ID => $uuid,
                    ModuleRule::MODULE_ID => $module->id,
                ]);
            }

            Transaction::commit();
        }

        catch (\Exception)
        {
            Transaction::rollback();
        }
    }
}
