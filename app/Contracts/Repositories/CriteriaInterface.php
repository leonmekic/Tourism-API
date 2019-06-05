<?php
namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Builder;

interface CriteriaInterface
{
    /**
     * Apply criteria to Builder object
     *
     * @param Builder $builder
     * @param Repository $repository
     *
     * @return void
     */
    public function apply(Builder $builder, Repository $repository);
}
