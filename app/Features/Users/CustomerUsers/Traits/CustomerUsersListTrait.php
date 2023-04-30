<?php

namespace App\Features\Users\CustomerUsers\Traits;

use App\Features\City\Cities\Infra\Models\City;
use App\Features\Persons\Infra\Models\Person;
use App\Features\Users\CustomerUsers\DTO\CustomerUsersFiltersDTO;
use App\Features\Users\CustomerUsers\Infra\Models\CustomerUser;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Users\Models\User;

trait CustomerUsersListTrait
{
    public function baseQuery()
    {
        return CustomerUser::select(
                CustomerUser::tableField(CustomerUser::ID). ' AS customer_user_id',
                CustomerUser::tableField(CustomerUser::VERIFIED_EMAIL). ' AS customer_user_verified_email',
                User::tableField(User::ID).' AS user_id',
                User::tableField(User::PERSON_ID).' AS person_id',
                User::tableField(User::NAME).' AS user_name',
                User::tableField(User::EMAIL).' AS user_email',
                User::tableField(User::ACTIVE).' AS user_active',
                User::tableField(User::CREATED_AT).' AS user_created_at',
                Profile::tableField(Profile::ID).' AS profile_id',
                Profile::tableField(Profile::UNIQUE_NAME).' AS profile_unique_name',
                Profile::tableField(Profile::DESCRIPTION).' AS profile_description',
                Person::tableField(Person::PHONE).' AS phone',
                Person::tableField(Person::ZIP_CODE).' AS zip_code',
                Person::tableField(Person::ADDRESS).' AS address',
                Person::tableField(Person::NUMBER_ADDRESS).' AS number_address',
                Person::tableField(Person::COMPLEMENT).' AS complement',
                Person::tableField(Person::DISTRICT).' AS district',
                City::tableField(City::ID).' AS city_id',
                City::tableField(City::DESCRIPTION).' AS city_description',
                City::tableField(City::UF).' AS uf',
            )
            ->leftJoin(
                User::tableName(),
                CustomerUser::tableField(CustomerUser::USER_ID),
                User::tableField(User::ID),
            )
            ->leftJoin(
                Person::tableName(),
                Person::tableField(Person::ID),
                User::tableField(User::PERSON_ID),
            )
            ->leftJoin(
                City::tableName(),
                City::tableField(City::ID),
                Person::tableField(Person::CITY_ID),
            )
            ->leftJoin(
                ProfileUser::tableName(),
                User::tableField(User::ID),
                ProfileUser::tableField(ProfileUser::USER_ID),
            )
            ->leftJoin(
                Profile::tableName(),
                ProfileUser::tableField(ProfileUser::PROFILE_ID),
                Profile::tableField(Profile::ID),
            );
    }

    public function baseQueryFilters(CustomerUsersFiltersDTO $customerUsersFiltersDTO)
    {
        return $this->baseQuery()
            ->when(!empty($customerUsersFiltersDTO->name),
                function($q) use($customerUsersFiltersDTO) {
                    return $q->where(
                        User::tableField(User::NAME),
                        'ilike',
                        "%{$customerUsersFiltersDTO->name}%"
                    );
                }
            )
            ->when(!empty($customerUsersFiltersDTO->email),
                function($q) use($customerUsersFiltersDTO) {
                    return $q->where(
                        User::tableField(User::EMAIL),
                        $customerUsersFiltersDTO->email
                    );
                }
            )
            ->when(!empty($customerUsersFiltersDTO->city),
                function($q) use($customerUsersFiltersDTO) {
                    return $q->where(
                        Person::tableField(Person::CITY_ID),
                        $customerUsersFiltersDTO->city
                    );
                }
            )
            ->when(!is_null($customerUsersFiltersDTO->active),
                function($q) use($customerUsersFiltersDTO) {
                    return $q->where(
                        User::tableField(User::ACTIVE),
                        $customerUsersFiltersDTO->active
                    );
                }
            );
    }
}
