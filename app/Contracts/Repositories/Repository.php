<?php
namespace App\Contracts\Repositories;

use Closure;
use App\Contracts\Repositories\CanApplyCriteria;
use App\Contracts\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Criterias\RawCriteria;

abstract class Repository
{
    use HasEvents, ApplyCriteriaTrait, FilterableTrait;

    /**
     * List of available fields
     *
     * @var array
     */
    protected static $fields = [];

    /**
     * Eloquent model
     *
     * @var \App\Contracts\Model
     */
    private $model = null;

    /**
     * Repository Model's primary key.
     * This value should be set on model initialization
     *
     * @var String
     */
    protected $primaryKey;

    /**
     * Return model name
     *
     * @return string
     */
    abstract public function getModelClass();

    /**
     * Return instance of EloquentModel
     *
     * @return \App\Contracts\Model
     */
    protected function model()
    {
        if (!$this->model) {
            $this->model = $this->getNewModel();
        }

        return $this->model;
    }

    protected function getNewModel()
    {
        $class = $this->getModelClass();
        $model = app()->make($class);

        $this->primaryKey = $model->getKeyName();

        return $model;
    }

    public function newQuery()
    {
        return $this->model()->newQuery()
            ->select($this->model()->getTable().'.*');
    }

    /**
     * Format payload data
     *
     * @param array $payload
     *
     * @return array
     */
    protected function normalizePayload(array $payload)
    {
        $repoClass = get_class($this);
        $allowed = array_flip(array_keys($repoClass::$fields));

        return array_intersect_key($payload, $allowed);
    }

    /**
     * Select all
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        $query = $this->applyCriteria($this->newQuery());

        return $query->get();
    }

    /**
     * Paginate results
     *
     * @param integer $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 1)
    {
        $query = $this->applyCriteria($this->newQuery());

        return $query->paginate($perPage)->appends(request()->except('_token'));
    }

    /**
     * Return single model
     *
     * @param mix $id
     *
     * @return \App\Contracts\Model
     */
    public function find($id)
    {
        return $this->findBy($this->getNewModel()->getKeyName(), $id);
    }

    /**
     * Return single model
     *
     * @param String $key
     * @param mix $value
     *
     * @return \App\Contracts\Model
     */
    public function findBy($key, $value)
    {
        $query = $this->applyCriteria($this->newQuery());

        return $query->where($key, $value)
            ->first();
    }

    /**
     * Return only one item depence on criterias
     *
     * @return \App\Contracts\Model
     */
    public function first()
    {
        $query = $this->applyCriteria($this->newQuery());

        return $query->first();
    }

    /**
     * Return Raw sql query
     *
     * @return String
     */
    public function toSql()
    {
        $query = $this->applyCriteria($this->newQuery());

        return $query->toSql();
    }

    /**
     * Select all escept
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allExcept($value)
    {
        $query = $this->applyCriteria($this->newQuery());

        if (!is_array($value)) {
            $value = [$value];
        }

        return $query->whereNotIn($this->getNewModel()->getKeyName(), $value)
            ->get();
    }

    /**
     * Create entry
     *
     * @param array $payload
     *
     * @return Model
     */
    public function create(array $payload)
    {
        $this->trigger('create#before');

        $model = $this->getNewModel();

        $model = $this->setModelPayload($model, $payload);

        $model->save();

        $this->trigger('create#after', [$model]);

        return $model;
    }

    /**
     * Update model with data
     *
     * @param Model $model
     * @param array $payload
     *
     * @return Model
     */
    public function update(Model $model, array $payload)
    {
        $this->trigger('update#before', [$model]);

        $model = $this->setModelPayload($model, $payload);

        $model->update();

        $this->trigger('update#after', [$model]);

        return $model;
    }

    /**
     * Sets model payload
     *
     * @param Model $model
     * @param array $payload
     *
     * @return Model
     */
    public function setModelPayload(Model $model, array $payload)
    {
        $payload = $this->normalizePayload($payload);

        foreach ($payload as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    public function delete(Model $model)
    {
        $this->trigger('delete#before', [$model]);

        $model->delete();

        $this->trigger('delete#after', [$model]);
    }

    public function __call($name, $args)
    {
        $expected_name = 'criteria'.ucfirst($name);

        if (is_callable([$this, $expected_name]) and method_exists($this, $expected_name)) {
            $this->pushCriteria(new RawCriteria(function ($builder) use ($expected_name, $args) {
                array_unshift($args, $builder);
                call_user_func_array([$this, $expected_name], $args);
            }));

            return $this;
        }

        $class = static::class;
        throw new \Exception("Call to undefined method {$class}::{$name}");
    }
}
