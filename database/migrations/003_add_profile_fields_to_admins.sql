-- Migration 003: Add profile fields to admins table
-- Adds full_name, email, bio, avatar, and updated_at columns
-- so admins can maintain a personal profile.
ALTER TABLE
  `admins`
ADD
  COLUMN `full_name` VARCHAR(120) NULL DEFAULT NULL
AFTER
  `username`,
ADD
  COLUMN `email` VARCHAR(180) NULL DEFAULT NULL
AFTER
  `full_name`,
ADD
  COLUMN `bio` TEXT NULL DEFAULT NULL
AFTER
  `email`,
ADD
  COLUMN `avatar` VARCHAR(255) NULL DEFAULT NULL
AFTER
  `bio`,
ADD
  COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
AFTER
  `created_at`;