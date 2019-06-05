<?php
namespace App\Contracts\Repositories;

use Illuminate\Database\Query\Builder;

interface CanApplyCriteria
{
    public function scopeApplyCriteria(Builder $builder, $criteria);
}
