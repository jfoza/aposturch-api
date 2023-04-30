<?php

namespace App\Features\Users\CustomerUsers\Infra\Repositories;

use App\Features\Base\Http\Pagination\PaginationOrder;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use App\Features\Users\CustomerUsers\DTO\CustomerUsersDTO;
use App\Features\Users\CustomerUsers\DTO\CustomerUsersFiltersDTO;
use App\Features\Users\CustomerUsers\Infra\Models\CustomerUser;
use App\Features\Users\CustomerUsers\Traits\CustomerUsersListTrait;
use App\Features\Users\Users\Models\User;

class CustomerUsersRepository implements CustomerUsersRepositoryInterface
{
    use CustomerUsersListTrait;

    public function findAll(CustomerUsersFiltersDTO $customerUsersFiltersDTO)
    {
        return $this
            ->baseQueryFilters($customerUsersFiltersDTO)
            ->orderBy(
                $this->defineColumnName($customerUsersFiltersDTO->paginationOrder),
                $customerUsersFiltersDTO->paginationOrder->getColumnOrder(),
            )
            ->paginate(
                $customerUsersFiltersDTO->paginationOrder->getPerPage()
            );
    }

    public function findById(string $customerUserId)
    {
        return $this
            ->baseQuery()
            ->where(User::tableField(User::ID), $customerUserId)
            ->first();
    }

    public function findByEmail(string $email)
    {
        return $this
            ->baseQuery()
            ->where(User::tableField(User::EMAIL), $email)
            ->first();
    }

    public function findByUserEmail(string $email)
    {
        return CustomerUser::with([
            'user' => function($q) {
                return $q->with(['person', 'profile']);
            }
        ])
        ->whereRelation(
            'user',
            User::EMAIL,
            '=',
            $email
        )
        ->first();
    }

    public function findByUserId(string $userId)
    {
        return CustomerUser::with(['user.person', 'user.profile'])
            ->whereRelation(
                'user',
                User::ID,
                '=',
                $userId
            )
            ->first();
    }

    public function create(CustomerUsersDTO $customerUsersDTO)
    {
        return CustomerUser::create([
            CustomerUser::USER_ID => $customerUsersDTO->userId,
            CustomerUser::VERIFIED_EMAIL => $customerUsersDTO->verifiedEmail,
        ]);
    }

    public function authorizeCustomerUser(string $customerUserId)
    {
        CustomerUser::where(CustomerUser::ID, $customerUserId)
            ->update([
                CustomerUser::VERIFIED_EMAIL => true
            ]);
    }

    private function defineColumnName(PaginationOrder $paginationOrder): string
    {
        if($paginationOrder->getColumnName() == 'created_at') {
            return User::tableField($paginationOrder->getColumnName());
        }

        return $paginationOrder->getColumnName();
    }
}
