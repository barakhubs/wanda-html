<?php

namespace App\Models;

/**
 * BlogPost model (namespaced)
 */
class BlogPost
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = \Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT id, title, slug, category, thumbnail, published, created_at
             FROM blog_posts ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public function getPublished(): array
    {
        $stmt = $this->db->query(
            'SELECT id, title, slug, category, excerpt, thumbnail, read_time, published, created_at
             FROM blog_posts WHERE published = 1 ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public function getFeatured(int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, category, excerpt, thumbnail, read_time, created_at
             FROM blog_posts WHERE published = 1 ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM blog_posts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM blog_posts WHERE slug = ? AND published = 1'
        );
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO blog_posts
             (title, slug, category, excerpt, body, thumbnail, read_time, published)
             VALUES (:title, :slug, :category, :excerpt, :body, :thumbnail, :read_time, :published)'
        );
        $stmt->execute([
            'title'     => $data['title'],
            'slug'      => $data['slug'],
            'category'  => $data['category'],
            'excerpt'   => $data['excerpt'],
            'body'      => $data['body'],
            'thumbnail' => $data['thumbnail'] ?? null,
            'read_time' => (int)($data['read_time'] ?? 5),
            'published' => (int)($data['published'] ?? 0),
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE blog_posts SET
               title     = :title,
               slug      = :slug,
               category  = :category,
               excerpt   = :excerpt,
               body      = :body,
               thumbnail = :thumbnail,
               read_time = :read_time,
               published = :published
             WHERE id = :id'
        );
        $stmt->execute([
            'title'     => $data['title'],
            'slug'      => $data['slug'],
            'category'  => $data['category'],
            'excerpt'   => $data['excerpt'],
            'body'      => $data['body'],
            'thumbnail' => $data['thumbnail'] ?? null,
            'read_time' => (int)($data['read_time'] ?? 5),
            'published' => (int)($data['published'] ?? 0),
            'id'        => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([$id]);
    }

    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, category, published, created_at
             FROM blog_posts ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn();
    }

    public function getSidebarPosts(int $excludeId, int $limit = 4): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, created_at
             FROM blog_posts
             WHERE published = 1 AND id != ?
             ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $excludeId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit,     \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
