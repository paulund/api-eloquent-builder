<?php

namespace Paulund\ApiQueryBuilder\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Paulund\ApiQueryBuilder\Tests\TestCase;

class RequestTest extends TestCase
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        Config::set('api-query-builder.parameters.include', 'include');
    }

    /** @test */
    public function it_asserts_true_when_filters_exist()
    {
        // Given
        request()->offsetSet('name', 'John');

        // When
        $availableFilters = [
            'name'
        ];

        // Then
        $this->assertTrue(Request::hasFilters($availableFilters));
    }

    /** @test */
    public function it_asserts_false_when_filters_dont_exist()
    {
        // Given
        request()->offsetSet('name', 'John');

        // When
        $availableFilters = [
            'unknown'
        ];

        // Then
        $this->assertFalse(Request::hasFilters($availableFilters));
    }
    
    /** @test */
    public function it_returns_all_filters()
    {
        // Given
        request()->offsetSet('name', 'John');
        request()->offsetSet('age', '30');
        request()->offsetSet('address', 'Street');

        // When
        $availableFilters = [
            'name',
            'age'
        ];

        // Then
        $this->assertEquals([
            'name' => 'John',
            'age' => '30',
        ], Request::getFilters($availableFilters));
    }

    /** @test */
    public function it_asserts_true_when_includes_exist()
    {
        // Given
        request()->offsetSet('include', 'address');

        // Then
        $this->assertTrue(Request::hasIncludes());
    }

    /** @test */
    public function it_asserts_false_when_includes_dont_exist()
    {
        $this->assertFalse(Request::hasIncludes());
    }

    /** @test */
    public function it_asserts_true_when_single_include_exist()
    {
        // Given
        request()->offsetSet('include', 'address');

        // Then
        $this->assertTrue(Request::hasInclude('address'));
    }

    /** @test */
    public function it_asserts_false_when_single_include_dont_exist()
    {
        // Given
        request()->offsetSet('include', 'name');

        // Then
        $this->assertFalse(Request::hasInclude('address'));
    }

    /** @test */
    public function it_asserts_true_when_single_include_exist_comma_separated()
    {
        // Given
        request()->offsetSet('include', 'address,name');

        // Then
        $this->assertTrue(Request::hasInclude('address'));
        $this->assertTrue(Request::hasInclude('name'));
    }
    
    /** @test */
    public function it_gets_all_includes()
    {
        // Given
        request()->offsetSet('include', 'address,name,unknown');

        // Then
        $this->assertEquals([
            'address',
            'name'
        ], Request::getIncludes(['address', 'name']));
    }
}