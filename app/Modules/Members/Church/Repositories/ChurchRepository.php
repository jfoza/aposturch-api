<?php

namespace App\Modules\Members\Church\Repositories;

use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\DTO\ChurchFiltersDTO;
use App\Modules\Members\Church\Models\Church;

class ChurchRepository implements ChurchRepositoryInterface
{
    public function findAll(ChurchFiltersDTO $churchFiltersDTO)
    {
        return Church::when(isset($churchFiltersDTO->name),
                function($q) use($churchFiltersDTO) {
                    return $q->where(
                        Church::tableField(Church::NAME),
                        $churchFiltersDTO->name
                    );
                }
            )
            ->when(isset($churchFiltersDTO->cityId),
                function($q) use($churchFiltersDTO) {
                    return $q->where(
                        Church::tableField(Church::CITY_ID),
                        $churchFiltersDTO->cityId
                    );
                }
            )
            ->get()
            ->toArray();
    }

    public function findById(string $churchId, bool $listMembers = false): mixed
    {
        if($listMembers)
        {
            return Church::with(['adminUser'])->where(Church::ID, $churchId)->first();
        }

        return Church::where(Church::ID, $churchId)->first();
    }

    public function create(ChurchDTO $churchDTO): Church
    {
        return Church::create([
            Church::NAME           => $churchDTO->name,
            Church::PHONE          => $churchDTO->phone,
            Church::EMAIL          => $churchDTO->email,
            Church::YOUTUBE        => $churchDTO->youtube,
            Church::FACEBOOK       => $churchDTO->facebook,
            Church::INSTAGRAM      => $churchDTO->instagram,
            Church::ZIP_CODE       => $churchDTO->zipCode,
            Church::ADDRESS        => $churchDTO->address,
            Church::NUMBER_ADDRESS => $churchDTO->numberAddress,
            Church::COMPLEMENT     => $churchDTO->complement,
            Church::DISTRICT       => $churchDTO->district,
            Church::UF             => $churchDTO->uf,
            Church::CITY_ID        => $churchDTO->cityId,
            Church::ACTIVE         => $churchDTO->active,
        ]);
    }

    public function save(ChurchDTO $churchDTO): Church
    {
        $update = [
            Church::ID             => $churchDTO->id,
            Church::NAME           => $churchDTO->name,
            Church::PHONE          => $churchDTO->phone,
            Church::EMAIL          => $churchDTO->email,
            Church::YOUTUBE        => $churchDTO->youtube,
            Church::FACEBOOK       => $churchDTO->facebook,
            Church::INSTAGRAM      => $churchDTO->instagram,
            Church::ZIP_CODE       => $churchDTO->zipCode,
            Church::ADDRESS        => $churchDTO->address,
            Church::NUMBER_ADDRESS => $churchDTO->numberAddress,
            Church::COMPLEMENT     => $churchDTO->complement,
            Church::DISTRICT       => $churchDTO->district,
            Church::UF             => $churchDTO->uf,
            Church::CITY_ID        => $churchDTO->cityId,
            Church::ACTIVE         => $churchDTO->active,
        ];

        Church::where(Church::ID, $churchDTO->id)->update($update);

        return Church::make($update);
    }

    public function remove(string $churchId): void
    {
        Church::where(Church::ID, $churchId)->delete();
    }
}
