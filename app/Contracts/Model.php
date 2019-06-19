<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public function scopeInAppItems($query)
    {
        $user = auth()->user();
        if ($user->email == 'superadmin@mail.com' || $user->id == 3) {
            return $query;
        }

        return $query->where('app_id', $user->app_id);
    }

    /**
     * Return model table name
     *
     * @return string
     */
    public static function getModelTable()
    {
        $class = get_called_class();

        return (new $class)->getTable();
    }

    /**
     * Return model primary key name
     *
     * @return string
     */
    public static function getModelKeyName()
    {
        $class = get_called_class();

        return (new $class)->getKeyName();
    }

    /**
     * arg $id => model id,
     * arg $relations => relation array to load on model instance
     * arg $relations_count => relation count to load on model instance
     **/
    public function findOrFailResolve($id, $relations = false, $relations_count = false)
    {
        $builder = $this->where(
            function ($query) use ($id) {
                $builder = $query->where($this->getKeyName(), $id);

                $model = $builder->getModel();

                if ($model and $model::isSluggable()) {
                    $builder = $builder->orWhere('slug', $id);
                }

                return $builder;
            }
        );

        if ($relations) {

            $builder = $builder->with($relations);
        }
        if ($relations_count) {

            $builder = $builder->withCount($relations_count);
        }

        return $builder->firstOrFail();
    }

    public function __call($method, $arguments)
    {
        if ($method === 'findOrFail') {

            return call_user_func_array([$this, 'findOrFailResolve'], $arguments);
        }

        return parent::__call($method, $arguments);
    }

    public static function __callStatic($method, $arguments)
    {
        if ($method === 'findOrFail') {

            $class = get_called_class();

            return call_user_func_array([new $class, 'findOrFailResolve'], $arguments);
        }

        return parent::__callStatic($method, $arguments);
    }

    public static function isSluggable()
    {
        return false;
    }
}
