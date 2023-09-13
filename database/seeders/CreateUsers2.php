<?php

namespace Database\Seeders;

use App\Features\City\Cities\Models\City;
use App\Features\Module\Modules\Models\Module;
use App\Features\Persons\Infra\Models\Person;
use App\Features\Users\ModulesUsers\Infra\Models\ModuleUser;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\ChurchesMembers\Models\ChurchMember;
use App\Modules\Membership\Members\Models\Member;
use App\Shared\Enums\ModulesUniqueNameEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Faker\Generator;

class CreateUsers2 extends Seeder
{
    private Generator $faker;
    private Collection $zipCodes;
    private mixed $churches;

    public function run(): void
    {
        $this->faker = app(Generator::class);

        $this->zipCodes = $this->getZipCodes();

        $this->churches = DB::table(Church::tableName())
            ->where(Church::ACTIVE, '=', true)
            ->pluck(Church::ID)
            ->toArray();

        Transaction::beginTransaction();

        try
        {
            $this->createAdminChurchUsers();
            $this->createAdminModuleUsers();
            $this->createAssistantUsers();

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            echo $e->getMessage();
        }
    }

    private function createAdminChurchUsers(): void
    {
        $profile = DB::table(Profile::tableName())
            ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_CHURCH->value)
            ->first();

        $modules = DB::table(Module::tableName())
            ->whereIn(
                Module::MODULE_UNIQUE_NAME,
                [
                    ModulesUniqueNameEnum::FINANCE,
                    ModulesUniqueNameEnum::MEMBERSHIP,
                    ModulesUniqueNameEnum::STORE,
                    ModulesUniqueNameEnum::GROUPS,
                    ModulesUniqueNameEnum::SCHEDULE,
                    ModulesUniqueNameEnum::PATRIMONY,
                ]
            )
            ->pluck(Module::ID)
            ->toArray();

        foreach ($this->churches as $key=>$churchId)
        {
            $city = DB::table(City::tableName())
                ->where(City::DESCRIPTION, $this->zipCodes[$key]->city)
                ->first();

            $person = Person::create([
                Person::CITY_ID        => $city->id,
                Person::PHONE          => Helpers::onlyNumbers($this->faker->phoneNumber),
                Person::ZIP_CODE       => $this->zipCodes[$key]->zipCode,
                Person::ADDRESS        => $this->zipCodes[$key]->address,
                Person::NUMBER_ADDRESS => $this->zipCodes[$key]->number,
                Person::COMPLEMENT     => '',
                Person::DISTRICT       => $this->zipCodes[$key]->district,
                Person::UF             => 'RS',
            ]);

            $user = User::create([
                User::PERSON_ID => $person->id,
                User::NAME      => $this->faker->name,
                User::EMAIL     => $this->faker->email,
                User::PASSWORD  => Hash::generateHash('Teste123'),
                User::ACTIVE    => true
            ]);

            $member = Member::create([
                Member::USER_ID        => $user->id,
                Member::CODE           => RandomStringHelper::numericGenerate(6),
                Member::ACTIVE         => true,
            ]);

            User::find($user->id)->module()->sync($modules);

            User::find($user->id)->profile()->sync([$profile->id]);

            ChurchMember::insert([
                ChurchMember::CHURCH_ID => $churchId,
                ChurchMember::MEMBER_ID => $member->id,
            ]);
        }
    }

    private function createAdminModuleUsers(): void
    {
        $profile = DB::table(Profile::tableName())
            ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MODULE->value)
            ->first();

        $modules = DB::table(Module::tableName())
            ->whereIn(
                Module::MODULE_UNIQUE_NAME,
                [
                    ModulesUniqueNameEnum::FINANCE,
                    ModulesUniqueNameEnum::MEMBERSHIP,
                    ModulesUniqueNameEnum::STORE,
                    ModulesUniqueNameEnum::GROUPS,
                    ModulesUniqueNameEnum::SCHEDULE,
                    ModulesUniqueNameEnum::PATRIMONY,
                ]
            )
            ->pluck(Module::ID)
            ->toArray();

        foreach ($this->churches as $churchId)
        {
            foreach ($modules as $key=>$moduleId)
            {
                $city = DB::table(City::tableName())
                    ->where(City::DESCRIPTION, $this->zipCodes[$key]->city)
                    ->first();

                $person = Person::create([
                    Person::CITY_ID        => $city->id,
                    Person::PHONE          => Helpers::onlyNumbers($this->faker->phoneNumber),
                    Person::ZIP_CODE       => $this->zipCodes[$key]->zipCode,
                    Person::ADDRESS        => $this->zipCodes[$key]->address,
                    Person::NUMBER_ADDRESS => $this->zipCodes[$key]->number,
                    Person::COMPLEMENT     => '',
                    Person::DISTRICT       => $this->zipCodes[$key]->district,
                    Person::UF             => 'RS',
                ]);

                $user = User::create([
                    User::PERSON_ID => $person->id,
                    User::NAME      => $this->faker->name,
                    User::EMAIL     => $this->faker->email,
                    User::PASSWORD  => Hash::generateHash('Teste123'),
                    User::ACTIVE    => true
                ]);

                $member = Member::create([
                    Member::USER_ID        => $user->id,
                    Member::CODE           => RandomStringHelper::numericGenerate(6),
                    Member::ACTIVE         => true,
                ]);

                ModuleUser::insert([
                    ModuleUser::MODULE_ID => $moduleId,
                    ModuleUser::USER_ID   => $user->id,
                ]);

                User::find($user->id)->profile()->sync([$profile->id]);

                ChurchMember::insert([
                    ChurchMember::CHURCH_ID => $churchId,
                    ChurchMember::MEMBER_ID => $member->id,
                ]);
            }
        }
    }

    private function createAssistantUsers(): void
    {
        $profile = DB::table(Profile::tableName())
            ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ASSISTANT->value)
            ->first();

        $modules = DB::table(Module::tableName())
            ->whereIn(
                Module::MODULE_UNIQUE_NAME,
                [
                    ModulesUniqueNameEnum::FINANCE,
                    ModulesUniqueNameEnum::MEMBERSHIP,
                    ModulesUniqueNameEnum::STORE,
                    ModulesUniqueNameEnum::GROUPS,
                    ModulesUniqueNameEnum::SCHEDULE,
                    ModulesUniqueNameEnum::PATRIMONY,
                ]
            )
            ->pluck(Module::ID)
            ->toArray();

        foreach ($this->churches as $key=>$churchId)
        {
            foreach ($this->zipCodes as $zipCode)
            {
                $city = DB::table(City::tableName())
                    ->where(City::DESCRIPTION, $zipCode->city)
                    ->first();

                $person = Person::create([
                    Person::CITY_ID        => $city->id,
                    Person::PHONE          => Helpers::onlyNumbers($this->faker->phoneNumber),
                    Person::ZIP_CODE       => $zipCode->zipCode,
                    Person::ADDRESS        => $zipCode->address,
                    Person::NUMBER_ADDRESS => $zipCode->number,
                    Person::COMPLEMENT     => '',
                    Person::DISTRICT       => $zipCode->district,
                    Person::UF             => 'RS',
                ]);

                $user = User::create([
                    User::PERSON_ID => $person->id,
                    User::NAME      => $this->faker->name,
                    User::EMAIL     => $this->faker->email,
                    User::PASSWORD  => Hash::generateHash('Teste123'),
                    User::ACTIVE    => true
                ]);

                $member = Member::create([
                    Member::USER_ID        => $user->id,
                    Member::CODE           => RandomStringHelper::numericGenerate(6),
                    Member::ACTIVE         => true,
                ]);

                ModuleUser::insert([
                    ModuleUser::MODULE_ID => $modules[$key],
                    ModuleUser::USER_ID   => $user->id,
                ]);

                User::find($user->id)->profile()->sync([$profile->id]);

                ChurchMember::insert([
                    ChurchMember::CHURCH_ID => $churchId,
                    ChurchMember::MEMBER_ID => $member->id,
                ]);
            }
        }
    }

    private function getZipCodes(): Collection
    {
        $json = json_decode(file_get_contents(database_path("seeders/scripts/zip.json")));

        return collect($json->zipCodes);
    }
}
