<?php

namespace App\Modules\Membership\Members\Traits;

use App\Features\City\Cities\Models\City;
use App\Features\Persons\Infra\Models\Person;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Enums\MembersDataAliasEnum;
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
            Member::tableField(Member::ID)             .' as '.MembersDataAliasEnum::MEMBER_ID,
            Member::tableField(Member::CODE)           .' as '.MembersDataAliasEnum::MEMBER_CODE,
            User::tableField(User::ID)                 .' as '.MembersDataAliasEnum::USER_ID,
            User::tableField(User::NAME)               .' as '.MembersDataAliasEnum::NAME,
            User::tableField(User::EMAIL)              .' as '.MembersDataAliasEnum::EMAIL,
            User::tableField(User::AVATAR_ID)          .' as '.MembersDataAliasEnum::AVATAR_ID,
            User::tableField(User::ACTIVE)             .' as '.MembersDataAliasEnum::ACTIVE,
            User::tableField(User::CREATED_AT)         .' as '.MembersDataAliasEnum::USER_CREATED_AT,
            Profile::tableField(Profile::ID)           .' as '.MembersDataAliasEnum::PROFILE_ID,
            Profile::tableField(Profile::DESCRIPTION)  .' as '.MembersDataAliasEnum::PROFILE_DESCRIPTION,
            Profile::tableField(Profile::UNIQUE_NAME)  .' as '.MembersDataAliasEnum::PROFILE_UNIQUE_NAME,
            Person::tableField(Person::ID)             .' as '.MembersDataAliasEnum::PERSON_ID,
            Person::tableField(Person::PHONE)          .' as '.MembersDataAliasEnum::PHONE,
            Person::tableField(Person::ADDRESS)        .' as '.MembersDataAliasEnum::ADDRESS,
            Person::tableField(Person::NUMBER_ADDRESS) .' as '.MembersDataAliasEnum::NUMBER_ADDRESS,
            Person::tableField(Person::COMPLEMENT)     .' as '.MembersDataAliasEnum::COMPLEMENT,
            Person::tableField(Person::DISTRICT)       .' as '.MembersDataAliasEnum::DISTRICT,
            Person::tableField(Person::ZIP_CODE)       .' as '.MembersDataAliasEnum::ZIP_CODE,
            Person::tableField(Person::CITY_ID)        .' as '.MembersDataAliasEnum::USER_CITY_ID,
            City::tableField(City::DESCRIPTION)        .' as '.MembersDataAliasEnum::USER_CITY_DESCRIPTION,
            City::tableField(City::UF)                 .' as '.MembersDataAliasEnum::UF,
        ];
    }
}
