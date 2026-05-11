<?php

/**
 * HomeGallery model
 */
class HomeGallery
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getPublished(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM home_gallery WHERE published = 1 ORDER BY sort_order ASC, id ASC'
        );
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM home_gallery ORDER BY sort_order ASC, id ASC'
        );
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM home_gallery WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO home_gallery (image_path, alt_text, is_wide, sort_order, published)
             VALUES (:image_path, :alt_text, :is_wide, :sort_order, :published)'
        );
        $stmt->execute([
            'image_path' => $data['image_path'],
            'alt_text'   => $data['alt_text'],
            'is_wide'    => (int) ($data['is_wide'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'published'  => (int) ($data['published'] ?? 1),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE home_gallery SET
               alt_text   = :alt_text,
               is_wide    = :is_wide,
               sort_order = :sort_order,
               published  = :published
             WHERE id = :id'
        );
        $stmt->execute([
            'alt_text'   => $data['alt_text'],
            'is_wide'    => (int) ($data['is_wide'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'published'  => (int) ($data['published'] ?? 1),
            'id'         => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM home_gallery WHERE id = ?');
        $stmt->execute([$id]);
    }
}
