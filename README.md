# Laravel API Query Builder
This Laravel package allows you to take the values in the query string and automatically apply
these to the Eloquent builder to filter the requests.

This will allow you to have a querystring of

```
/api/products?name=software
```

This will allow you to filter the products by name of software.

If you want to eager load a relationship with the query then you can do so by using an include query.

```
/api/products?name=software&include=price
```
This will automatically filter by name and eager load the price relationship.

## How Does This Work?
This package is inspired by

- [L5 Repository](https://github.com/andersao/l5-repository)
- [Spatie Laravel Query Builder](https://github.com/spatie/laravel-query-builder)

Both of these packages allow you to filter the results of eloquent from the querystring. They are
both easily setup and easy to use. But I believe they are heavier than they need to be, therefore
I created this package to use a more Laravel native approach to the problem. The package is made up
of 2 files.

The uses the custom Eloquent Builders and request macros to extend the request object.

*Laravel5 Repository* package is built with using the repository pattern with Laravel, this allows
you to place a class before the data source for methods you can reuse for common queries. This can already be
done in different ways using just from extending Eloquent. There are lots of other features to the package
the one feature we're refactoring is the way to assign [RequestCriteria](https://github.com/andersao/l5-repository#using-the-requestcriteria).

This allows you to setup a class to add all your Eloquent where methods and match them to filters, this is
exactly what custom Eloquent builders can do. The benefit of using eloquent builder for this is that you avoid
duplicate code of having common where queries in the model, repositories or the criteria.

This package allows you to reduce all this down to one area the custom Eloquent builder.

The custom eloquent builder is also a native alternative to the repository pattern, as it gives you a
centralised location for common queries just from using native Laravel classes.

*Spatie Laravel Query Builder* is a good alternative to L5 Repository and reuse a lot of the Laravel features. But
when looking through the source code I noticed it was doing a lot of things that are already built into Laravel.
I decided to refactor this to a simpler codebase and reuse more Laravel native.

## Custom Eloquent Builder
A custom eloquent builder is a class you can create which will extend the inbuilt eloquent builder object.
You can use this to create common query methods.

```
<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Builder;

class ProductBuilder extends Builder
{
    /**
     * @param $name
     * @return ProductBuilder
     */
    public function name($name)
    {
        return $this->where('name', $name);
    }
}
```

In your model you will override the `newEloquentBuilder` method to return the new `ProductBuilder`.

```
<?php

namespace App\Models;

use App\Eloquent\ProductBuilder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @return ProductBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new ProductBuilder($query);
    }
}
```

You can now use this method on your Eloquent models.

```
$products = Product::name('software');
```

This works in a similar way to [Laravel Scopes](https://laravel.com/docs/7.x/eloquent#query-scopes)

### Filtering Results
To be able to filter results, first create your model's new eloquent builder.

Add the `newEloquentBuilder` method to your model to return the new builder.

Add a method to query the results.

```
/**
 * @param $name
 * @return ProductBuilder
 */
public function name($name)
{
    return $this->where('name', $name);
}
```

In your Eloquent builder class add a property `$filters`.

```
/**
 * Which filters are available to the model
 *
 * @var array
 */
protected $filters = [
    'name'
];
```

Add the model `get` method to retrieve the models

```
Product::get();
```

Access the endpoint for this controller

```
/api/products?name=software
```

The results of `get()` will now also add a query where for `name = software`.

### Include Relationships
To use eager load includes it works in a similar way to filtering.

On the custom eloquent builder you will need to add the available includes to the package.

```
/**
 * Which includes are available to the model
 *
 * @var array
 */
protected $includes = [
    'prices'
];
```

Now you can add a prices method to the model for the relationship.

```
/**
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function prices()
{
    return $this->hasMany(Price::class);
}
```

When you access the endpoint with a include querystring eloquent will automatically eager
load the prices relationship.

```
/api/products?include=prices
```

## Installation
You can install the package via composer:

```
composer require paulund/api-eloquent-builder
```

## Tests
To run the package tests

```
composer test
```

To run with code coverage

```
composer test-coverage
```

## License
The MIT License (MIT). Please see License File for more information.
