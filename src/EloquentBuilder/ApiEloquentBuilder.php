<?php

namespace Paulund\ApiQueryBuilder\EloquentBuilder;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class ApiEloquentBuilder
 * @package Paulund\ApiQueryBuilder\EloquentBuilder
 */
abstract class ApiEloquentBuilder extends Builder
{
    /**
     * Which filters are available to the model
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Which includes are available to the model
     *
     * @var array
     */
    protected $includes = [];

    /**
     * Execute the query as a "select" statement.
     *
     * @param string[] $columns
     * @return \Illuminate\Database\Eloquent\Collection|ApiEloquentBuilder[]|void
     */
    public function get($columns = ['*'])
    {
        $this->apply();
        return parent::get($columns);
    }

    /**
     * Paginate the given query.
     *
     * @param null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->apply();
        return parent::paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * Apply the filters and includes
     */
    private function apply()
    {
        foreach (request()->getFilters($this->filters) ?? [] as $method => $value) {
            if (!method_exists($this, $method)) {
                continue;
            }

            $this->{$method}($value);
        }

        if (request()->hasIncludes()) {
            foreach (request()->getIncludes($this->includes) ?? [] as $include) {
                $this->with($include);
            }
        }
    }
}
