<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find record by ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find record by ID or fail
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new record
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update record
     */
    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    /**
     * Delete record
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Find records by field
     */
    public function findByField(string $field, $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Find first record by field
     */
    public function findFirstByField(string $field, $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }

    /**
     * Count records
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if record exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get fresh model instance
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Create query builder
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Get records with relationships
     */
    public function with(array $relations): Builder
    {
        return $this->model->with($relations);
    }

    /**
     * Get latest records
     */
    public function latest(string $column = 'created_at'): Builder
    {
        return $this->model->newQuery()->latest($column);
    }

    /**
     * Get oldest records
     */
    public function oldest(string $column = 'created_at'): Builder
    {
        return $this->model->newQuery()->oldest($column);
    }
}