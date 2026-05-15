<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Admin;

/**
 * Integration tests for the Admin model.
 *
 * These tests run against the real database configured in .env.
 * They validate read operations and exercise write operations on a
 * temporary row so the existing admin account is never modified.
 */
class AdminTest extends TestCase
{
    private Admin $model;

    /** ID of the temporary admin row created for write tests. */
    private static int $tempId = 0;

    protected function setUp(): void
    {
        $this->model = new Admin();
    }

    // ── getById ───────────────────────────────────────────────────────────────

    public function testGetByIdReturnsNullForMissingRecord(): void
    {
        $result = $this->model->getById(PHP_INT_MAX);
        $this->assertNull($result);
    }

    public function testGetByIdReturnsArrayWithExpectedKeys(): void
    {
        // Seed a temporary admin for this assertion
        $id = $this->seedTempAdmin('__test_getbyid__');
        $this->assertGreaterThan(0, $id, 'Seed row could not be created — check DB connection.');

        try {
            $row = $this->model->getById($id);
            $this->assertIsArray($row);
            foreach (['id', 'username', 'full_name', 'email', 'bio', 'avatar', 'created_at'] as $key) {
                $this->assertArrayHasKey($key, $row, "Expected key '{$key}' in admin record.");
            }
        } finally {
            $this->deleteTempAdmin($id);
        }
    }

    // ── updateProfile ─────────────────────────────────────────────────────────

    public function testUpdateProfilePersistsChanges(): void
    {
        $id = $this->seedTempAdmin('__test_update_profile__');
        $this->assertGreaterThan(0, $id);

        try {
            $result = $this->model->updateProfile(
                $id,
                'Test Admin Name',
                'test.admin@example.com',
                'A brief bio for testing.',
                null
            );

            $this->assertTrue($result, 'updateProfile() should return true on success.');

            $row = $this->model->getById($id);
            $this->assertNotNull($row);
            $this->assertSame('Test Admin Name',             $row['full_name']);
            $this->assertSame('test.admin@example.com',      $row['email']);
            $this->assertSame('A brief bio for testing.',    $row['bio']);
        } finally {
            $this->deleteTempAdmin($id);
        }
    }

    public function testUpdateProfilePersistsAvatar(): void
    {
        $id = $this->seedTempAdmin('__test_update_avatar__');
        $this->assertGreaterThan(0, $id);

        try {
            $avatarPath = 'uploads/profile/fake_avatar.jpg';
            $result     = $this->model->updateProfile($id, 'Avatar Admin', '', '', $avatarPath);

            $this->assertTrue($result);

            $row = $this->model->getById($id);
            $this->assertNotNull($row);
            $this->assertSame($avatarPath, $row['avatar']);
        } finally {
            $this->deleteTempAdmin($id);
        }
    }

    // ── changePassword ────────────────────────────────────────────────────────

    public function testChangePasswordFailsForWrongCurrentPassword(): void
    {
        $id = $this->seedTempAdmin('__test_pw_wrong__');
        $this->assertGreaterThan(0, $id);

        try {
            $result = $this->model->changePassword($id, 'definitely_wrong_password', 'NewPass123!');
            $this->assertIsString($result, 'Should return an error message string on wrong current password.');
            $this->assertStringContainsStringIgnoringCase('incorrect', $result);
        } finally {
            $this->deleteTempAdmin($id);
        }
    }

    public function testChangePasswordSucceedsWithCorrectCurrentPassword(): void
    {
        $id = $this->seedTempAdmin('__test_pw_correct__', 'OldPass123!');
        $this->assertGreaterThan(0, $id);

        try {
            $result = $this->model->changePassword($id, 'OldPass123!', 'NewPass456!');
            $this->assertTrue($result, 'changePassword() should return true on success.');
        } finally {
            $this->deleteTempAdmin($id);
        }
    }

    public function testChangePasswordReturnsErrorForNonExistentAdmin(): void
    {
        $result = $this->model->changePassword(PHP_INT_MAX, 'any', 'any');
        $this->assertIsString($result);
    }

    // ── updateUsername ────────────────────────────────────────────────────────

    public function testUpdateUsernameSucceedsForUniqueUsername(): void
    {
        $id = $this->seedTempAdmin('__test_uname_orig__');
        $this->assertGreaterThan(0, $id);

        try {
            $newUsername = '__test_uname_new_' . time() . '__';
            $result      = $this->model->updateUsername($id, $newUsername);
            $this->assertTrue($result);

            $row = $this->model->getById($id);
            $this->assertSame($newUsername, $row['username']);
        } finally {
            $this->deleteTempAdmin($id);
        }
    }

    public function testUpdateUsernameFailsWhenUsernameTaken(): void
    {
        $idA = $this->seedTempAdmin('__test_taken_a__');
        $idB = $this->seedTempAdmin('__test_taken_b__');
        $this->assertGreaterThan(0, $idA);
        $this->assertGreaterThan(0, $idB);

        try {
            // Attempt to give admin B the same username as admin A
            $result = $this->model->updateUsername($idB, '__test_taken_a__');
            $this->assertIsString($result, 'Should return error string when username is already taken.');
        } finally {
            $this->deleteTempAdmin($idA);
            $this->deleteTempAdmin($idB);
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Insert a temporary admin row for testing and return its ID.
     * Returns 0 if the insert fails (e.g. database not available).
     */
    private function seedTempAdmin(string $username, string $plainPassword = 'TempPass99!'): int
    {
        try {
            $db   = \Database::getInstance();
            $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
            $stmt = $db->prepare(
                'INSERT INTO admins (username, password_hash) VALUES (?, ?)'
            );
            $stmt->execute([$username, $hash]);
            return (int) $db->lastInsertId();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** Remove a temporary admin row by ID. */
    private function deleteTempAdmin(int $id): void
    {
        if ($id <= 0) {
            return;
        }
        try {
            $db = \Database::getInstance();
            $db->prepare('DELETE FROM admins WHERE id = ?')->execute([$id]);
        } catch (\Throwable) {
            // Best-effort cleanup; ignore errors.
        }
    }
}
