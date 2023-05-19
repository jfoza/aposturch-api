<?php

namespace App\Modules\Membership\Members\Traits;

use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Views\MembersDataView;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\HigherOrderWhenProxy;

trait MembersListsTrait
{
    public function getBaseQueryBuilder(): QueryBuilder|EloquentBuilder
    {
        return MembersDataView::select(
                MembersDataView::MEMBER_ID,
                MembersDataView::USER_ID,
                MembersDataView::PERSON_ID,
                MembersDataView::PROFILE_DESCRIPTION,
                MembersDataView::PROFILE_UNIQUE_NAME,
                MembersDataView::NAME,
                MembersDataView::EMAIL,
                MembersDataView::PHONE,
                MembersDataView::ADDRESS,
                MembersDataView::NUMBER_ADDRESS,
                MembersDataView::COMPLEMENT,
                MembersDataView::DISTRICT,
                MembersDataView::ZIP_CODE,
                MembersDataView::USER_CITY_DESCRIPTION,
                MembersDataView::UF,
                MembersDataView::CHURCHES,
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
                isset($membersFiltersDTO->profileId),
                fn($q) => $q->where(MembersDataView::PROFILE_ID, $membersFiltersDTO->profileId)
            )
            ->when(
                isset($membersFiltersDTO->name),
                fn($q) => $q->where(MembersDataView::NAME, 'ilike',"%{$membersFiltersDTO->name}%")
            )
            ->when(
                isset($membersFiltersDTO->cityId),
                fn($q) => $q->where(MembersDataView::USER_CITY_ID, $membersFiltersDTO->cityId)
            );
    }
}
