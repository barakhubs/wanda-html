<?php

/**
 * BlogPost model
 */
class BlogPost
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** All posts (admin use — includes unpublished). Excludes body LONGTEXT; edit form uses getById(). */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT id, title, slug, category, thumbnail, published, created_at
             FROM blog_posts ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /** Published posts for the public listing page. Excludes body LONGTEXT. */
    public function getPublished(): array
    {
        $stmt = $this->db->query(
            'SELECT id, title, slug, category, excerpt, thumbnail, read_time, published, created_at
             FROM blog_posts WHERE published = 1 ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /** N most-recent published posts (homepage / featured strip). Excludes body LONGTEXT. */
    public function getFeatured(int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, category, excerpt, thumbnail, read_time, created_at
             FROM blog_posts WHERE published = 1 ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
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

    /**
     * Create a new post.
     * @param array $data  Keys: title, slug, category, excerpt, body, thumbnail, read_time, published
     * @return int  New post ID
     */
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
            'read_time' => (int) ($data['read_time'] ?? 5),
            'published' => (int) ($data['published'] ?? 0),
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing post.
     * @param array $data  Same keys as create()
     */
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
            'read_time' => (int) ($data['read_time'] ?? 5),
            'published' => (int) ($data['published'] ?? 0),
            'id'        => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM blog_posts WHERE id = ?');
        $stmt->execute([$id]);
    }

    /** Most-recent $limit posts regardless of published status (admin dashboard use). */
    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, category, published, created_at
             FROM blog_posts ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn();
    }

    /**
     * Sidebar posts: recent published, excluding the currently-viewed post.
     * Only fetches the 3 columns the sidebar template actually renders —
     * avoids pulling LONGTEXT body and large excerpt fields.
     */
    public function getSidebarPosts(int $excludeId, int $limit = 4): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, created_at
             FROM blog_posts
             WHERE published = 1 AND id != ?
             ORDER BY created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
