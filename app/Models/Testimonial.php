<?php

namespace App\Models;

class Testimonial
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = \Database::getInstance();
    }

    public function getPublished(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM testimonials WHERE published = 1 ORDER BY sort_order ASC, id ASC'
        );
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC'
        );
        return $stmt->fetchAll();
    }

    public function getAllPaged(int $page, int $perPage): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC LIMIT ? OFFSET ?'
        );
        $stmt->execute([$perPage, ($page - 1) * $perPage]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM testimonials WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO testimonials
             (quote, author_initials, author_role, author_org, sort_order, published)
             VALUES
             (:quote, :author_initials, :author_role, :author_org, :sort_order, :published)'
        );
        $stmt->execute([
            'quote'           => $data['quote'],
            'author_initials' => strtoupper($data['author_initials']),
            'author_role'     => $data['author_role'],
            'author_org'      => $data['author_org'],
            'sort_order'      => (int)($data['sort_order'] ?? 0),
            'published'       => (int)($data['published'] ?? 1),
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE testimonials SET
               quote           = :quote,
               author_initials = :author_initials,
               author_role     = :author_role,
               author_org      = :author_org,
               sort_order      = :sort_order,
               published       = :published
             WHERE id = :id'
        );
        $stmt->execute([
            'quote'           => $data['quote'],
            'author_initials' => strtoupper($data['author_initials']),
            'author_role'     => $data['author_role'],
            'author_org'      => $data['author_org'],
            'sort_order'      => (int)($data['sort_order'] ?? 0),
            'published'       => (int)($data['published'] ?? 1),
            'id'              => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM testimonials WHERE id = ?')->execute([$id]);
    }

    public function count(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM testimonials')->fetchColumn();
    }
}
