<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Portfolio;

/**
 * Integration tests for the Portfolio model.
 */
class PortfolioTest extends TestCase
{
    private Portfolio $model;

    protected function setUp(): void
    {
        $this->model = new Portfolio();
    }

    public function testCountReturnsNonNegativeInteger(): void
    {
        $count = $this->model->count();
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testGetAllReturnsArray(): void
    {
        $items = $this->model->getAll();
        $this->assertIsArray($items);
    }

    public function testGetPublishedReturnsArray(): void
    {
        $items = $this->model->getPublished();
        $this->assertIsArray($items);
    }

    public function testGetPublishedOnlyIncludesPublished(): void
    {
        $items = $this->model->getPublished();
        foreach ($items as $item) {
            $this->assertEquals(1, (int) $item['published'], 'Unpublished item returned by getPublished()');
        }
    }

    public function testGetFeaturedRespectLimit(): void
    {
        $limit = 2;
        $items = $this->model->getFeatured($limit);
        $this->assertIsArray($items);
        $this->assertLessThanOrEqual($limit, count($items));
    }

    public function testGetByIdReturnsNullForMissingRecord(): void
    {
        $result = $this->model->getById(PHP_INT_MAX);
        $this->assertNull($result);
    }
}
