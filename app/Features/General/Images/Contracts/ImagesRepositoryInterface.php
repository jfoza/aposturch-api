<?php

namespace App\Features\General\Images\Contracts;

use App\Features\General\Images\DTO\ImagesDTO;

interface ImagesRepositoryInterface
{
    public function findById(string $id);
    public function create(ImagesDTO $imagesDTO): object;
    public function save(ImagesDTO $imagesDTO);
    public function remove(string $id);
}
