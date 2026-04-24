<?php

namespace App\Contracts\Repository;

interface BaseRepositoryInterface
{
    public function all(): mixed;
    public function find(int $id): mixed;
    public function create(array $data): mixed;
    public function update(int $id, array $data): mixed;
    public function delete(int $id): bool;
}
