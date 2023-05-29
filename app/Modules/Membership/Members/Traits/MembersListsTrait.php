<?php

namespace App\Modules\Membership\Members\Traits;

use App\Features\City\Cities\Models\City;
use App\Features\Persons\Infra\Models\Person;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Models\Member;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\HigherOrderWhenProxy;

trait MembersListsTrait
{
    public function getBaseQueryBuilder(): QueryBuilder|EloquentBuilder
    {
        return Member::with(['church'])
            ->select($this->getSelectColumns())
            ->join(
                User::tableName(),
                User::tableField(User::ID),
                Member::tableField(Member::USER_ID)
            )
            ->join(
                ProfileUser::tableName(),
                ProfileUser::tableField(ProfileUser::USER_ID),
                User::tableField(User::ID)
            )
            ->join(
                Profile::tableName(),
                Profile::tableField(Profile::ID),
                ProfileUser::tableField(ProfileUser::PROFILE_ID)
            )
            ->join(
                Person::tableName(),
                Person::tableField(Person::ID),
                User::tableField(User::PERSON_ID)
            )
            ->leftJoin(
                City::tableName(),
                City::tableField(City::ID),
                Person::tableField(Person::CITY_ID)
            );
    }

    /**
     * @param MembersFiltersDTO $membersFiltersDTO
     * @return QueryBuilder|EloquentBuilder|HigherOrderWhenProxy
     */
    public function baseQueryBuilderFilters(MembersFiltersDTO $membersFiltersDTO): QueryBuilder|EloquentBuilder|HigherOrderWhenProxy
    {
        return $this->getBaseQueryBuilder()
            ->when(
                isset($membersFiltersDTO->name),
                fn($q) => $q->where(User::tableField(User::NAME), 'ilike',"%{$membersFiltersDTO->name}%")
            )
            ->when(
                isset($membersFiltersDTO->email),
                fn($q) => $q->where(User::tableField(User::EMAIL), $membersFiltersDTO->email)
            )
            ->when(
                isset($membersFiltersDTO->phone),
                fn($q) => $q->where(Person::tableField(Person::PHONE), $membersFiltersDTO->phone)
            )
            ->when(
                isset($membersFiltersDTO->cityId),
                fn($q) => $q->where(City::tableField(City::ID), $membersFiltersDTO->cityId)
            )
            ->when(
                isset($membersFiltersDTO->profileId),
                fn($q) => $q->where(Profile::tableField(Profile::ID), $membersFiltersDTO->profileId)
            )
            ->when(
                isset($membersFiltersDTO->churchIds),
                fn($q) => $q->whereHas(
                    'church',
                    fn($c) => $c->whereIn(Church::tableField(Church::ID), $membersFiltersDTO->churchIds)
                )
            );
    }

    public function getSelectColumns(): array
    {
        return [
            Member::tableField(Member::ID),
            Member::tableField(Member::ID)             .' as member_id',
            Member::tableField(Member::CODE)           .' as member_code',
            User::tableField(User::ID)                 .' as user_id',
            User::tableField(User::NAME)               .' as name',
            User::tableField(User::EMAIL)              .' as email',
            User::tableField(User::ACTIVE)             .' as active',
            User::tableField(User::CREATED_AT)         .' as user_created_at',
            Profile::tableField(Profile::ID)           .' as profile_id',
            Profile::tableField(Profile::DESCRIPTION)  .' as profile_description',
            Profile::tableField(Profile::UNIQUE_NAME)  .' as profile_unique_name',
            Person::tableField(Person::ID)             .' as person_id',
            Person::tableField(Person::PHONE)          .' as phone',
            Person::tableField(Person::ADDRESS)        .' as address',
            Person::tableField(Person::NUMBER_ADDRESS) .' as number_address',
            Person::tableField(Person::COMPLEMENT)     .' as complement',
            Person::tableField(Person::DISTRICT)       .' as district',
            Person::tableField(Person::ZIP_CODE)       .' as zip_code',
            Person::tableField(Person::CITY_ID)        .' as user_city_id',
            City::tableField(City::DESCRIPTION)        .' as user_city_description',
            City::tableField(City::UF)                 .' as uf',
        ];
    }
}
