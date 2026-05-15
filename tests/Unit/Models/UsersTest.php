<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Admin;

/**
 * Integration tests for Admin model user-management methods.
 *
 * All write operations use temporary rows that are cleaned up in finally blocks
 * so the real admin account is never touched.
 */
class UsersTest extends TestCase
{
    private Admin $model;

    protected function setUp(): void
    {
        $this->model = new Admin();
    }

    // ── getAll ────────────────────────────────────────────────────────────────

    public function testGetAllReturnsArray(): void
    {
        $result = $this->model->getAll();
        $this->assertIsArray($result);
    }

    public function testGetAllRowsHaveExpectedKeys(): void
    {
        $rows = $this->model->getAll();
        if (empty($rows)) {
            $this->markTestSkipped('No rows in admins table to inspect.');
        }
        foreach (['id', 'username', 'role', 'full_name', 'email'] as $key) {
            $this->assertArrayHasKey($key, $rows[0], "Expected key '{$key}' in getAll() row.");
        }
    }

    // ── getMembers ────────────────────────────────────────────────────────────

    public function testGetMembersReturnsOnlyMemberRows(): void
    {
        $id = $this->seedMember('__test_getmembers__');
        $this->assertGreaterThan(0, $id);

        try {
            $members = $this->model->getMembers();
            $this->assertIsArray($members);
            foreach ($members as $row) {
                $this->assertSame('member', $row['role']);
            }
        } finally {
            $this->deleteSeed($id);
        }
    }

    // ── countAll ──────────────────────────────────────────────────────────────

    public function testCountAllReturnsNonNegativeInteger(): void
    {
        $count = $this->model->countAll();
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testCountAllIncreasesAfterCreate(): void
    {
        $before = $this->model->countAll();
        $id     = $this->seedMember('__test_count_member__');
        $this->assertGreaterThan(0, $id);

        try {
            $after = $this->model->countAll();
            $this->assertSame($before + 1, $after);
        } finally {
            $this->deleteSeed($id);
        }
    }

    // ── getByIdWithRole ───────────────────────────────────────────────────────

    public function testGetByIdWithRoleReturnsNullForMissingRecord(): void
    {
        $this->assertNull($this->model->getByIdWithRole(PHP_INT_MAX));
    }

    public function testGetByIdWithRoleContainsRoleKey(): void
    {
        $id = $this->seedMember('__test_getrole__');
        $this->assertGreaterThan(0, $id);

        try {
            $row = $this->model->getByIdWithRole($id);
            $this->assertIsArray($row);
            $this->assertArrayHasKey('role', $row);
            $this->assertSame('member', $row['role']);
        } finally {
            $this->deleteSeed($id);
        }
    }

    // ── createMember ─────────────────────────────────────────────────────────

    public function testCreateMemberReturnsIntegerId(): void
    {
        $id = $this->model->createMember(
            '__test_create_' . time() . '__',
            'SecurePass1!',
            'Test Member',
            'test@member.example'
        );

        $this->assertIsInt($id, 'createMember() should return an int ID on success.');
        $this->assertGreaterThan(0, $id);

        $this->deleteSeed($id);
    }

    public function testCreateMemberFailsForDuplicateUsername(): void
    {
        $id = $this->seedMember('__test_dup_create__');
        $this->assertGreaterThan(0, $id);

        try {
            $result = $this->model->createMember(
                '__test_dup_create__', // same username
                'AnotherPass1!',
                'Duplicate Member',
                ''
            );
            $this->assertIsString($result, 'Should return error string on duplicate username.');
        } finally {
            $this->deleteSeed($id);
        }
    }

    public function testCreateMemberSetsRoleToMember(): void
    {
        $username = '__test_role_check_' . time() . '__';
        $id       = $this->model->createMember($username, 'SecurePass1!', 'Role Checker', '');
        $this->assertGreaterThan(0, $id);

        try {
            $row = $this->model->getByIdWithRole($id);
            $this->assertSame('member', $row['role']);
        } finally {
            $this->deleteSeed($id);
        }
    }

    // ── updateMember ─────────────────────────────────────────────────────────

    public function testUpdateMemberPersistsChanges(): void
    {
        $id = $this->seedMember('__test_update_member__');
        $this->assertGreaterThan(0, $id);

        try {
            $newUsername = '__test_updated_' . time() . '__';
            $result      = $this->model->updateMember($id, $newUsername, 'Updated Name', 'new@email.test');
            $this->assertTrue($result);

            $row = $this->model->getByIdWithRole($id);
            $this->assertSame($newUsername,      $row['username']);
            $this->assertSame('Updated Name',    $row['full_name']);
            $this->assertSame('new@email.test',  $row['email']);
        } finally {
            $this->deleteSeed($id);
        }
    }

    public function testUpdateMemberRejectsAdminRows(): void
    {
        // The first row in the database should be the super-admin
        $rows = $this->model->getAll();
        $adminRow = null;
        foreach ($rows as $r) {
            if ($r['role'] === 'admin') {
                $adminRow = $r;
                break;
            }
        }

        if ($adminRow === null) {
            $this->markTestSkipped('No admin-role row found — cannot test protection.');
        }

        $result = $this->model->updateMember($adminRow['id'], 'hacked', 'Hacked', '');
        $this->assertIsString($result, 'updateMember() must refuse to touch admin-role rows.');
    }

    public function testUpdateMemberFailsForDuplicateUsername(): void
    {
        $idA = $this->seedMember('__test_upd_a__');
        $idB = $this->seedMember('__test_upd_b__');
        $this->assertGreaterThan(0, $idA);
        $this->assertGreaterThan(0, $idB);

        try {
            $result = $this->model->updateMember($idB, '__test_upd_a__', 'Name', '');
            $this->assertIsString($result);
        } finally {
            $this->deleteSeed($idA);
            $this->deleteSeed($idB);
        }
    }

    // ── deleteMember ─────────────────────────────────────────────────────────

    public function testDeleteMemberRemovesRow(): void
    {
        $id = $this->seedMember('__test_delete_member__');
        $this->assertGreaterThan(0, $id);

        $result = $this->model->deleteMember($id);
        $this->assertTrue($result);
        $this->assertNull($this->model->getByIdWithRole($id));
    }

    public function testDeleteMemberRejectsAdminRows(): void
    {
        $rows = $this->model->getAll();
        $adminRow = null;
        foreach ($rows as $r) {
            if ($r['role'] === 'admin') {
                $adminRow = $r;
                break;
            }
        }

        if ($adminRow === null) {
            $this->markTestSkipped('No admin-role row found — cannot test protection.');
        }

        $result = $this->model->deleteMember($adminRow['id']);
        $this->assertIsString($result, 'deleteMember() must refuse to delete admin-role rows.');

        // Confirm row still exists
        $this->assertNotNull($this->model->getByIdWithRole($adminRow['id']));
    }

    public function testDeleteMemberReturnsErrorForNonExistentId(): void
    {
        $result = $this->model->deleteMember(PHP_INT_MAX);
        $this->assertIsString($result);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function seedMember(string $username, string $password = 'TempPass99!'): int
    {
        try {
            $db   = \Database::getInstance();
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $db->prepare(
                "INSERT INTO admins (username, role, password_hash) VALUES (?, 'member', ?)"
            )->execute([$username, $hash]);
            return (int) $db->lastInsertId();
        } catch (\Throwable) {
            return 0;
        }
    }

    private function deleteSeed(int $id): void
    {
        if ($id <= 0) return;
        try {
            \Database::getInstance()->prepare('DELETE FROM admins WHERE id = ?')->execute([$id]);
        } catch (\Throwable) {
        }
    }
}
