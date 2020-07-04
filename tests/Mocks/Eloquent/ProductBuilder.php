<?php

namespace Paulund\ApiQueryBuilder\Tests\Mocks\Eloquent;

use Paulund\ApiQueryBuilder\EloquentBuilder\ApiEloquentBuilder;

class ProductBuilder extends ApiEloquentBuilder
{
    /**
     * Which filters are available to the model
     *
     * @var array
     */
    protected $filters = [
        'name',
        'price'
    ];

    /**
     * Which includes are available to the model
     *
     * @var array
     */
    protected $includes = [
        'prices'
    ];

    /**
     * @param $name
     * @return ProductBuilder
     */
    public function name($name)
    {
        return $this->where('name', $name);
    }

    /**
     * @param $price
     * @return ProductBuilder
     */
    public function price($price)
    {
        return $this->whereHas('prices', function ($query) use ($price) {
            $query->where('price', $price);
        });
    }
}
