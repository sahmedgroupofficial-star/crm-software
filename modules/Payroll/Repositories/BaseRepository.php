<?php

namespace Modules\Payroll\Repositories;

use App\Contracts\Repository\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model) {}

    public function all(): mixed { return $this->model->all(); }
    public function find(int $id): mixed { return $this->model->findOrFail($id); }
    public function create(array $data): mixed { return $this->model->create($data); }
    public function update(int $id, array $data): mixed { $m = $this->find($id); $m->update($data); return $m; }
    public function delete(int $id): bool { return $this->find($id)->delete(); }
}
