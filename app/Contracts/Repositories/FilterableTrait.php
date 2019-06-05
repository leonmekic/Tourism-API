<?php
namespace App\Contracts\Repositories;

use App\Contracts\Http\FilterContract;

trait FilterableTrait
{
    public function applyHttpFilter(FilterContract $filter)
    {
        $filter->apply($this);

        return $this;
    }
}
