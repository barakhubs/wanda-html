-- Migration 004: Add role column to admins table and create admin_roles lookup
-- Adds a role column ('admin' | 'member') so the super-admin can create
-- member accounts with restricted access (content only — no settings/users).
ALTER TABLE
  `admins`
ADD
  COLUMN `role` ENUM('admin', 'member') NOT NULL DEFAULT 'admin'
AFTER
  `username`;
-- Ensure every existing row retains the admin role (safe no-op on fresh installs).
UPDATE
  `admins`
SET
  `role` = 'admin'
WHERE
  `role` IS NULL
  OR `role` = '';