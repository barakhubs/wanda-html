<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Report;

class ReportTest extends TestCase
{
    private Report $model;

    protected function setUp(): void
    {
        $this->model = new Report();
    }

    public function testCountReturnsNonNegativeInteger(): void
    {
        $this->assertGreaterThanOrEqual(0, $this->model->count());
    }

    public function testGetAllReturnsArray(): void
    {
        $this->assertIsArray($this->model->getAll());
    }

    public function testGetPublishedReturnsArray(): void
    {
        $this->assertIsArray($this->model->getPublished());
    }

    public function testGetPublishedOnlyIncludesPublished(): void
    {
        $rows = $this->model->getPublished();
        if (empty($rows)) {
            $this->assertIsArray($rows); // table may be empty in a fresh install
            return;
        }
        foreach ($rows as $r) {
            $this->assertEquals(1, (int) $r['published']);
        }
    }

    public function testGetByIdReturnsNullForMissingRecord(): void
    {
        $this->assertNull($this->model->getById(PHP_INT_MAX));
    }

    public function testGetBySlugReturnsNullForMissingRecord(): void
    {
        $this->assertNull($this->model->getBySlug('__nonexistent__report__'));
    }
}
