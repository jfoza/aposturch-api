<?php

namespace App\Modules\Membership\Members\Services;

use App\Features\Base\Services\Service;
use App\Modules\Membership\Members\Contracts\FindAllMembersServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllMembersService extends Service implements FindAllMembersServiceInterface
{
    public function __construct(
        private readonly MembersRepositoryInterface $membersRepository
    ) {}

    public function execute(MembersFiltersDTO $membersFiltersDTO): LengthAwarePaginator|Collection
    {
        return $this->membersRepository->findAll($membersFiltersDTO);
    }
}
