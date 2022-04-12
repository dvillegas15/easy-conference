<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use Firevel\Firestore\Facades\Firestore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;
    protected $with;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all($related = null)
    {
        return $this->model->when($related, function ($query) use ($related) {
            return $query->with($related);
        })->get();
    }

    protected function getQuery(array $params = [], $related = null)
    {
        $keys = array_keys(Arr::only($params, $this->getFields()));
        $query = $this->model->query();

        foreach ($keys as $key) {
            $query->when($key, function ($query) use ($params, $key) {
                return $query->where($key, $params[$key]);
            });
        }

        $query->when($related, function ($query) use ($related) {
            return $query->with($related);
        });

        $query->when(isset($params['sort_by']), function ($query) use ($params) {
            $dir = $params['sort_dir'] ?? 'asc';
            return $query->orderBy($params['sort_by'], $dir);
        });

        return $query;
    }

    public function get(array $params = [], $related = null, $appends = null)
    {
        $query = $this->getQuery($params, $related);

        $items = isset($params['current_page']) && isset($params['per_page'])
            ? $query->paginate($params['per_page'])
            : $query->get();

        $items = $this->setAppends($items, $appends);

        return $items;
    }

    protected function setAppends($items, $appends)
    {
        if(!$appends) return $items;

        $appends = is_array($appends)? $appends: explode(',', $appends);
        $items->each->setAppends($appends);

        return $items;
    }

    public function find($id, $related = null, $appends = null)
    {
        $item = $this->model->when($related, function ($query) use ($related) {
            return $query->with($related);
        })->find($id);

        if($appends) {
            $appends = is_array($appends)? $appends: explode(',', $appends);
            $item->setAppends($appends);
        }

        return $item;
    }
    public function findOrFail($id, $related = null)
    {
        return $this->model->when($related, function ($query) use ($related) {
            return $query->with($related);
        })->findOrFail($id);
    }

    public function create(array $data)
    {
        $filteredData = Arr::only($data, $this->getFields());
        return $this->model::create($filteredData);
    }

    public function update($id, array $data)
    {
        $filteredData = Arr::only($data, $this->getFields());

        $item = is_numeric($id) ? $this->model->when(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->model)), function ($query) {
            return $query->withTrashed();
        })->findOrFail($id) : $id;

        $item->update($filteredData);
        return $item;
    }

    public function delete($id): void
    {
        $item = $this->model->findOrFail($id);
        $item->delete();
    }

    public function deleteBy(array $params = [])
    {
        $query = $this->getQuery($params);
        return $query->delete();
    }

    public function with($relations)
    {
        $relations && $this->model->with(is_string($relations) ? func_get_args() : $relations);
        return $this;
    }

    public function getFields()
    {
        return Schema::getColumnListing($this->model->getTable());
    }

    public function getQueryFields()
    {
        $table = $this->getTable();
        return array_map(function ($item) use ($table) {
            return "{$table}.{$item}";
        }, Schema::getColumnListing($table));
    }

    protected function filterData($data)
    {
        return Arr::only($data, $this->getFields());
    }

    public function getTable()
    {
        return $this->model->getTable();
    }

    public function new(array $data)
    {
        $filteredData = Arr::only($data, $this->getFields());
        return new $this->model($filteredData);
    }

    public function save(Model $model)
    {
        return $model->save();
    }

    public function fireSave($model)
    {
        $model = is_numeric($model) ? $this->find($model) : $model;
        if (method_exists($model, 'loadRelationships')) {
            $model->loadRelationships();
        }
    }

    public function first(array $params = [])
    {
        return $this->get($params)->first();
    }

    public function updateOrCreate(array $params, array $data = [], $withTrashed = false)
    {
        return $this->model->when($withTrashed, function ($query) {
            return $query->withTrashed();
        })->updateOrCreate($params, $data);
    }

    public function getWithTrashed(array $params = [], $related = null, $page = 0, $perPage = 0, $sortBy = '', $sortDesc = false)
    {
        $query = $this->model->query()->withTrashed();

        $keys = array_keys($params);

        foreach ($keys as $key) {
            $query->where($key, $params[$key]);
        }

        if ($related) {
            $query->with($related);
        }

        if ($page && $perPage) {
            return $query->paginate($perPage);
        }
        return $query->get();
    }

    public function firstWithTrashed(array $params = [], $related = null)
    {
        return $this->getWithTrashed($params, $related)->first();
    }

    public function exists(array $params = [], $withTrashed = false)
    {
        $keys = array_keys(Arr::only($params, $this->getFields()));
        $query = $this->model->query();

        foreach ($keys as $key) {
            $query->when($key, function ($query) use ($params, $key) {
                return $query->where($key, $params[$key]);
            });
        }

        $query->when($withTrashed, function($query) {
            return $query->withTrashed();
        });

        return $query->exists();
    }

    public function count(array $params = [])
    {
        $query = $this->getQuery($params);
        return $query->count();
    }
}
