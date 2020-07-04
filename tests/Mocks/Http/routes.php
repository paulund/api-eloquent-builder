<?php

Route::get('products', '\Paulund\ApiQueryBuilder\Tests\Mocks\Http\Controllers\ProductController@index')->name('test.products');