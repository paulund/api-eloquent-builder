<?php

namespace Paulund\ApiQueryBuilder\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Paulund\ApiQueryBuilder\Tests\Mocks\Models\Price;
use Paulund\ApiQueryBuilder\Tests\Mocks\Models\Product;
use Paulund\ApiQueryBuilder\Tests\TestCase;

class EndpointTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Config::set('api-query-builder.parameters.include', 'include');
    }

    /** @test */
    public function it_includes_relationship_from_request()
    {
        // Given
        $product = factory(Product::class)->create();
        $prices = factory(Price::class, 5)->make([
            'product_id' => $product->id
        ]);
        $product->prices()->saveMany($prices);

        // When
        $response = $this->json('get', route('test.products', [
            'include' => 'prices'
        ]));

        // Then
        $response->assertSuccessful();
        $response->assertJsonStructure([
            '*' => [
                [
                    'id',
                    'name',
                    'prices' => [
                        [
                            'id',
                            'product_id',
                            'price',
                        ]
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_uses_querystring_to_filter_query()
    {
        // Given
        $products = factory(Product::class, 5)->create();

        // When
        $response = $this->json('get', route('test.products', [
            'name' => $products->first()->name
        ]));

        // Then
        $response->assertSuccessful();
        $this->assertCount(1, $response->decodeResponseJson()['data']);
        $response->assertJsonStructure([
            '*' => [
                [
                    'id',
                    'name',
                ]
            ]
        ]);
    }
}
