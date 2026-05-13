<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\BlogPost;

/**
 * Integration tests for the BlogPost model.
 * These tests run against the real database configured in .env.
 */
class BlogPostTest extends TestCase
{
    private BlogPost $model;

    protected function setUp(): void
    {
        $this->model = new BlogPost();
    }

    public function testCountReturnsNonNegativeInteger(): void
    {
        $count = $this->model->count();
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testGetAllReturnsArray(): void
    {
        $posts = $this->model->getAll();
        $this->assertIsArray($posts);
    }

    public function testGetPublishedReturnsArray(): void
    {
        $posts = $this->model->getPublished();
        $this->assertIsArray($posts);
    }

    public function testGetPublishedOnlyIncludesPublished(): void
    {
        $posts = $this->model->getPublished();
        foreach ($posts as $post) {
            $this->assertEquals(1, (int) $post['published'], 'Unpublished post returned by getPublished()');
        }
    }

    public function testGetFeaturedRespectLimit(): void
    {
        $limit = 2;
        $posts = $this->model->getFeatured($limit);
        $this->assertIsArray($posts);
        $this->assertLessThanOrEqual($limit, count($posts));
    }

    public function testGetByIdReturnsNullForMissingRecord(): void
    {
        $result = $this->model->getById(PHP_INT_MAX);
        $this->assertNull($result);
    }

    public function testGetBySlugReturnsNullForMissingRecord(): void
    {
        $result = $this->model->getBySlug('__nonexistent__slug__');
        $this->assertNull($result);
    }

    public function testGetRecentHonorsLimit(): void
    {
        $limit = 3;
        $posts = $this->model->getRecent($limit);
        $this->assertIsArray($posts);
        $this->assertLessThanOrEqual($limit, count($posts));
    }
}
