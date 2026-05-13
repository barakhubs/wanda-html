<?php

namespace App\Models;

class TeamMember
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = \Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM team_members WHERE published = 1 ORDER BY sort_order ASC, id ASC'
        );
        return $this->attachSkills($stmt->fetchAll());
    }

    public function getAllAdmin(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM team_members ORDER BY sort_order ASC, id ASC'
        );
        return $this->attachSkills($stmt->fetchAll());
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM team_members WHERE id = ?');
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        if (!$member) return null;
        $member['skills'] = $this->getSkills($id);
        return $member;
    }

    private function getSkills(int $memberId): array
    {
        $stmt = $this->db->prepare(
            'SELECT skill_name FROM team_skills WHERE member_id = ? ORDER BY sort_order ASC'
        );
        $stmt->execute([$memberId]);
        return array_column($stmt->fetchAll(), 'skill_name');
    }

    private function attachSkills(array $members): array
    {
        if (empty($members)) return [];
        $ids  = array_column($members, 'id');
        $in   = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare(
            "SELECT member_id, skill_name FROM team_skills
             WHERE member_id IN ({$in}) ORDER BY sort_order ASC"
        );
        $stmt->execute($ids);
        $skillMap = [];
        foreach ($stmt->fetchAll() as $row) {
            $skillMap[$row['member_id']][] = $row['skill_name'];
        }
        foreach ($members as &$m) {
            $m['skills'] = $skillMap[$m['id']] ?? [];
        }
        return $members;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO team_members
             (name, role, bio_1, bio_2, photo, gradient_css, fallback_icon, sort_order, published)
             VALUES
             (:name, :role, :bio_1, :bio_2, :photo, :gradient_css, :fallback_icon, :sort_order, :published)'
        );
        $stmt->execute([
            'name'          => $data['name'],
            'role'          => $data['role'],
            'bio_1'         => $data['bio_1'],
            'bio_2'         => $data['bio_2'] ?? null,
            'photo'         => $data['photo'] ?? null,
            'gradient_css'  => $data['gradient_css'],
            'fallback_icon' => $data['fallback_icon'] ?? 'bi-person-fill',
            'sort_order'    => (int)($data['sort_order'] ?? 0),
            'published'     => (int)($data['published'] ?? 1),
        ]);
        $id = (int)$this->db->lastInsertId();
        $this->saveSkills($id, $data['skills'] ?? []);
        return $id;
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE team_members SET
               name          = :name,
               role          = :role,
               bio_1         = :bio_1,
               bio_2         = :bio_2,
               photo         = :photo,
               gradient_css  = :gradient_css,
               fallback_icon = :fallback_icon,
               sort_order    = :sort_order,
               published     = :published
             WHERE id = :id'
        );
        $stmt->execute([
            'name'          => $data['name'],
            'role'          => $data['role'],
            'bio_1'         => $data['bio_1'],
            'bio_2'         => $data['bio_2'] ?? null,
            'photo'         => $data['photo'] ?? null,
            'gradient_css'  => $data['gradient_css'],
            'fallback_icon' => $data['fallback_icon'] ?? 'bi-person-fill',
            'sort_order'    => (int)($data['sort_order'] ?? 0),
            'published'     => (int)($data['published'] ?? 1),
            'id'            => $id,
        ]);
        $this->db->prepare('DELETE FROM team_skills WHERE member_id = ?')->execute([$id]);
        $this->saveSkills($id, $data['skills'] ?? []);
    }

    public function saveSkills(int $memberId, array $skills): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO team_skills (member_id, skill_name, sort_order) VALUES (?, ?, ?)'
        );
        foreach ($skills as $i => $skill) {
            $skill = trim($skill);
            if ($skill !== '') {
                $stmt->execute([$memberId, $skill, $i]);
            }
        }
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM team_members WHERE id = ?')->execute([$id]);
    }

    public function count(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM team_members')->fetchColumn();
    }
}
