<?php

namespace App\Contracts\Repositories;

trait ApplyCriteriaTrait
{
    /**
     * Criteria list
     *
     * @var array
     */
    protected $criteria = [];

    /**
     * Skip criteria flag
     *
     * @var boolean
     */
    protected $skipCriteria = false;

    public function withoutCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * Push criteria to stock
     *
     * @param CriteriaInterface $criteria
     *
     * @return $this
     */
    public function pushCriteria(CriteriaInterface $criteria)
    {
        $this->criteria[] = $criteria;

        return $this;
    }

    /**
     * Add single critera.
     * DO NOT USE THIS METHOD
     * Use `pushCriteria` instead of this
     *
     * @deprecated
     *
     * @param CriteriaInterface $criteria
     *
     * @return $this
     */
    public function getByCriteria(CriteriaInterface $criteria)
    {
        return $this->pushCriteria($criteria);
    }

    /**
     * Return all criteria for current repo
     *
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Flush all criteria for this repo
     *
     * @return array
     */
    public function flushCriteria()
    {
        return array_splice($this->criteria, 0);
    }

    public function applyCriteria($query)
    {
        if ($this->skipCriteria === true) {
            return $query;
        }

        foreach ($this->getCriteria() as $criteria) {
            $criteria->apply($query, $this);
        }

        $this->criteria = [];

        return $query;
    }
}
