<?php

namespace App\Models;

/**
 * Admin model — handles profile retrieval and update for the admins table.
 */
class Admin
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = \Database::getInstance();
    }

    /**
     * Fetch a single admin record by ID.
     * Returns null when no matching row is found.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, username, role, full_name, email, bio, avatar, created_at, updated_at
               FROM admins
              WHERE id = ?
              LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Update the non-credential profile fields for an admin.
     *
     * @param int    $id
     * @param string $fullName
     * @param string $email
     * @param string $bio
     * @param string|null $avatar  Relative path, or null to leave unchanged.
     */
    public function updateProfile(int $id, string $fullName, string $email, string $bio, ?string $avatar): bool
    {
        if ($avatar !== null) {
            $stmt = $this->db->prepare(
                'UPDATE admins
                    SET full_name  = ?,
                        email      = ?,
                        bio        = ?,
                        avatar     = ?,
                        updated_at = NOW()
                  WHERE id = ?'
            );
            return $stmt->execute([$fullName, $email, $bio, $avatar, $id]);
        }

        $stmt = $this->db->prepare(
            'UPDATE admins
                SET full_name  = ?,
                    email      = ?,
                    bio        = ?,
                    updated_at = NOW()
              WHERE id = ?'
        );
        return $stmt->execute([$fullName, $email, $bio, $id]);
    }

    /**
     * Change the password for an admin.
     * Returns false when the current password does not match.
     */
    public function changePassword(int $id, string $currentPassword, string $newPassword): bool|string
    {
        $stmt = $this->db->prepare('SELECT password_hash FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return 'Admin account not found.';
        }

        if (!password_verify($currentPassword, $row['password_hash'])) {
            return 'Current password is incorrect.';
        }

        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->db->prepare('UPDATE admins SET password_hash = ?, updated_at = NOW() WHERE id = ?')
            ->execute([$hash, $id]);

        return true;
    }

    /**
     * Update the username for an admin.
     * Returns an error string when the username is already taken by another account.
     */
    public function updateUsername(int $id, string $username): bool|string
    {
        $stmt = $this->db->prepare('SELECT id FROM admins WHERE username = ? AND id != ? LIMIT 1');
        $stmt->execute([$username, $id]);
        if ($stmt->fetch()) {
            return 'That username is already taken.';
        }

        $this->db->prepare('UPDATE admins SET username = ?, updated_at = NOW() WHERE id = ?')
            ->execute([$username, $id]);

        return true;
    }

    // ── User management (admin-only operations) ───────────────────────────────

    /**
     * Return all admin/member accounts ordered by role then username.
     * Excludes password_hash for safety.
     */
    public function getAll(): array
    {
        return $this->db->query(
            "SELECT id, username, role, full_name, email, avatar, created_at, updated_at
               FROM admins
              ORDER BY FIELD(role,'admin','member'), username ASC"
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Return all member accounts only.
     */
    public function getMembers(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, username, role, full_name, email, avatar, created_at, updated_at
               FROM admins
              WHERE role = 'member'
              ORDER BY username ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Fetch a full record including the role column.
     */
    public function getByIdWithRole(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, username, role, full_name, email, bio, avatar, created_at, updated_at
               FROM admins
              WHERE id = ?
              LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create a new member account.
     * Returns the new row's ID, or an error string on failure.
     */
    public function createMember(
        string $username,
        string $password,
        string $fullName,
        string $email
    ): int|string {
        // Uniqueness check
        $stmt = $this->db->prepare('SELECT id FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return 'That username is already taken.';
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare(
            "INSERT INTO admins (username, role, password_hash, full_name, email)
             VALUES (?, 'member', ?, ?, ?)"
        );
        $stmt->execute([$username, $hash, $fullName, $email]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update a member account (admin action).
     * Optionally resets the password if $newPassword is non-empty.
     * Returns true or an error string.
     */
    public function updateMember(
        int $id,
        string $username,
        string $fullName,
        string $email,
        string $newPassword = ''
    ): bool|string {
        // Prevent editing a super-admin via this method
        $row = $this->getByIdWithRole($id);
        if (!$row) {
            return 'User not found.';
        }
        if ($row['role'] === 'admin') {
            return 'Super-admin accounts cannot be edited through user management.';
        }

        // Uniqueness check for the new username
        $stmt = $this->db->prepare('SELECT id FROM admins WHERE username = ? AND id != ? LIMIT 1');
        $stmt->execute([$username, $id]);
        if ($stmt->fetch()) {
            return 'That username is already taken.';
        }

        if ($newPassword !== '') {
            $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
            $this->db->prepare(
                'UPDATE admins
                    SET username = ?, full_name = ?, email = ?,
                        password_hash = ?, updated_at = NOW()
                  WHERE id = ?'
            )->execute([$username, $fullName, $email, $hash, $id]);
        } else {
            $this->db->prepare(
                'UPDATE admins
                    SET username = ?, full_name = ?, email = ?,
                        updated_at = NOW()
                  WHERE id = ?'
            )->execute([$username, $fullName, $email, $id]);
        }

        return true;
    }

    /**
     * Delete a member account.
     * Super-admin accounts are protected from deletion via this method.
     * Returns true or an error string.
     */
    public function deleteMember(int $id): bool|string
    {
        $row = $this->getByIdWithRole($id);
        if (!$row) {
            return 'User not found.';
        }
        if ($row['role'] === 'admin') {
            return 'Super-admin accounts cannot be deleted through user management.';
        }

        $this->db->prepare('DELETE FROM admins WHERE id = ?')->execute([$id]);
        return true;
    }

    /**
     * Total count of all accounts (used for pagination / stats).
     */
    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    }
}
