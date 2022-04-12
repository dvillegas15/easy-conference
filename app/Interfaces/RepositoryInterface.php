<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all($related = null);
    public function get(array $params = [], $related = null, $appends = null);
    public function find($id, $related = null, $appends = null);
    public function findOrFail($id, $related = null);
    public function create(array $data);
    public function new(array $data);
    public function save(Model $model);
    /**
     * Actualiza el modelo
     * @param $id int|Model id del modelo o modelo que se va a actualizar
     * @param $data array datos que se van a actualizar del modelo
     */
    public function update($id, array $data);
    public function delete($id): void;
    public function deleteBy(array $params = []);
    public function with($relations);
    public function getFields();
    public function getTable();
    public function getQueryFields();
    public function fireSave($model);
    public function first(array $params = []);
    public function updateOrCreate(array $params, array $data = []);
    public function count(array $params = []);

    public function getWithTrashed(array $params = [], $related = null, $page = 0, $perPage = 0, $sortBy = '', $sortDesc = false);
    public function firstWithTrashed(array $params = [], $related = null);
    public function exists(array $params = [], $withTrashed = false);
}
