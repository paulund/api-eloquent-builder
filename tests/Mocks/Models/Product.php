<?php

namespace Paulund\ApiQueryBuilder\Tests\Mocks\Models;

use Illuminate\Database\Eloquent\Model;
use Paulund\ApiQueryBuilder\Tests\Mocks\Eloquent\ProductBuilder;

class Product extends Model
{
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * @return ProductBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new ProductBuilder($query);
    }
}