<?php

namespace App\Contracts\Service;

interface BaseServiceInterface
{
    public function all(): mixed;
    public function findById(int $id): mixed;
    public function create(array $data): mixed;
    public function update(int $id, array $data): mixed;
    public function delete(int $id): bool;
}
