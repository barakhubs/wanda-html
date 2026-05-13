<?php

namespace App\Models;

class Portfolio
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = \Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT id, title, slug, category, short_desc, thumbnail,
                    gradient_css, icon_class, featured, sort_order, published
             FROM portfolio_items ORDER BY sort_order ASC, id ASC'
        );
        return $stmt->fetchAll();
    }

    public function getPublished(): array
    {
        $stmt = $this->db->query(
            'SELECT id, title, slug, category, short_desc, full_desc, thumbnail,
                    gradient_css, icon_class, featured, sort_order, published
             FROM portfolio_items WHERE published = 1 ORDER BY sort_order ASC, id ASC'
        );
        return $stmt->fetchAll();
    }

    public function getFeatured(int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, category, short_desc, thumbnail,
                    gradient_css, icon_class, featured, sort_order
             FROM portfolio_items
             WHERE published = 1
             ORDER BY featured DESC, sort_order ASC, id ASC
             LIMIT ?'
        );
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM portfolio_items WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO portfolio_items
             (title, slug, category, short_desc, full_desc, thumbnail, gradient_css, icon_class, featured, sort_order, published)
             VALUES
             (:title, :slug, :category, :short_desc, :full_desc, :thumbnail, :gradient_css, :icon_class, :featured, :sort_order, :published)'
        );
        $stmt->execute([
            'title'        => $data['title'],
            'slug'         => $data['slug'],
            'category'     => $data['category'],
            'short_desc'   => $data['short_desc'],
            'full_desc'    => $data['full_desc'],
            'thumbnail'    => $data['thumbnail'] ?? null,
            'gradient_css' => $data['gradient_css'],
            'icon_class'   => $data['icon_class'] ?? 'bi-camera',
            'featured'     => (int)($data['featured'] ?? 0),
            'sort_order'   => (int)($data['sort_order'] ?? 0),
            'published'    => (int)($data['published'] ?? 1),
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE portfolio_items SET
               title        = :title,
               slug         = :slug,
               category     = :category,
               short_desc   = :short_desc,
               full_desc    = :full_desc,
               thumbnail    = :thumbnail,
               gradient_css = :gradient_css,
               icon_class   = :icon_class,
               featured     = :featured,
               sort_order   = :sort_order,
               published    = :published
             WHERE id = :id'
        );
        $stmt->execute([
            'title'        => $data['title'],
            'slug'         => $data['slug'],
            'category'     => $data['category'],
            'short_desc'   => $data['short_desc'],
            'full_desc'    => $data['full_desc'],
            'thumbnail'    => $data['thumbnail'] ?? null,
            'gradient_css' => $data['gradient_css'],
            'icon_class'   => $data['icon_class'] ?? 'bi-camera',
            'featured'     => (int)($data['featured'] ?? 0),
            'sort_order'   => (int)($data['sort_order'] ?? 0),
            'published'    => (int)($data['published'] ?? 1),
            'id'           => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM portfolio_items WHERE id = ?')->execute([$id]);
    }

    public function count(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM portfolio_items')->fetchColumn();
    }
}
