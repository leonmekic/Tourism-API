<?php
namespace App\Criterias;

use App\Contracts\Repositories\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\Repositories\Repository;

class RawCriteria implements CriteriaInterface
{
    /**
     * Rule list
     *
     * @var Closure
     */
    protected $callback;

    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Apply criteria to Builder object
     *
     * @param Builder $builder
     * @param Repository $repository
     *
     * @return void
     */
    public function apply(Builder $builder, Repository $repository)
    {
        $func = $this->callback;

        $func($builder, $repository);
    }
}
