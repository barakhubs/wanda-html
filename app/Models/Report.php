<?php

namespace App\Models;

class Report
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = \Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT id, title, slug, category, excerpt, pdf_path, cover_path, published, created_at
             FROM reports ORDER BY created_at DESC'
        )->fetchAll();
    }

    public function getPublished(): array
    {
        return $this->db->query(
            'SELECT id, title, slug, category, excerpt, pdf_path, cover_path, created_at
             FROM reports WHERE published = 1 ORDER BY created_at DESC'
        )->fetchAll();
    }

    public function getPublishedByCategory(string $category): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug, category, excerpt, pdf_path, cover_path, created_at
             FROM reports WHERE published = 1 AND category = ?
             ORDER BY created_at DESC'
        );
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM reports WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM reports WHERE slug = ? AND published = 1'
        );
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO reports (title, slug, category, excerpt, pdf_path, cover_path, published)
             VALUES (:title, :slug, :category, :excerpt, :pdf_path, :cover_path, :published)'
        );
        $stmt->execute([
            'title'      => $data['title'],
            'slug'       => $data['slug'],
            'category'   => $data['category'],
            'excerpt'    => $data['excerpt'],
            'pdf_path'   => $data['pdf_path'],
            'cover_path' => $data['cover_path'] ?? null,
            'published'  => (int)($data['published'] ?? 0),
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE reports SET
               title      = :title,
               slug       = :slug,
               category   = :category,
               excerpt    = :excerpt,
               pdf_path   = :pdf_path,
               cover_path = :cover_path,
               published  = :published
             WHERE id = :id'
        );
        $stmt->execute([
            'title'      => $data['title'],
            'slug'       => $data['slug'],
            'category'   => $data['category'],
            'excerpt'    => $data['excerpt'],
            'pdf_path'   => $data['pdf_path'],
            'cover_path' => $data['cover_path'] ?? null,
            'published'  => (int)($data['published'] ?? 0),
            'id'         => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM reports WHERE id = ?')->execute([$id]);
    }

    public function count(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM reports')->fetchColumn();
    }
}
