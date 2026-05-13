-- ============================================================
-- Wanda Communications Uganda — Database Schema
-- Run this in phpMyAdmin or via MySQL CLI:
--   CREATE DATABASE wanda_communications CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
--   USE wanda_communications;
--   SOURCE schema.sql;
-- ============================================================
SET
  FOREIGN_KEY_CHECKS = 0;
-- NOTE: sort_order uses SMALLINT UNSIGNED (0–65535) — wide enough for any realistic
  --       content set while still being indexed efficiently.
  -- ── Admin users ───────────────────────────────────────────────────────────────
  CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(80) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- ── Testimonials ──────────────────────────────────────────────────────────────
  CREATE TABLE IF NOT EXISTS `testimonials` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `quote` TEXT NOT NULL,
    `author_initials` VARCHAR(10) NOT NULL,
    `author_role` VARCHAR(120) NOT NULL,
    `author_org` VARCHAR(160) NOT NULL,
    `sort_order` SMALLINT UNSIGNED DEFAULT 0,
    `published` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- ── Blog posts ────────────────────────────────────────────────────────────────
  CREATE TABLE IF NOT EXISTS `blog_posts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `category` ENUM(
      'storytelling',
      'advocacy',
      'digital',
      'strategy'
    ) NOT NULL DEFAULT 'storytelling',
    `excerpt` TEXT NOT NULL,
    `body` LONGTEXT NOT NULL,
    `thumbnail` VARCHAR(255) DEFAULT NULL,
    `read_time` TINYINT UNSIGNED DEFAULT 5,
    `published` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- slug lookup: exact unique match — UNIQUE index already covers it.
    -- (published, created_at) covering index: satisfies the public listing query
    --   WHERE published = 1 ORDER BY created_at DESC
    -- without accessing the row at all for the sort step.
    INDEX `idx_slug` (`slug`),
    INDEX `idx_published` (`published`),
    INDEX `idx_category` (`category`),
    INDEX `idx_pub_date` (`published`, `created_at`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- ── Portfolio items ───────────────────────────────────────────────────────────
  CREATE TABLE IF NOT EXISTS `portfolio_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `category` ENUM(
      'photography',
      'videography',
      'advocacy',
      'reports'
    ) NOT NULL,
    `short_desc` VARCHAR(255) NOT NULL,
    `full_desc` TEXT NOT NULL,
    `thumbnail` VARCHAR(255) DEFAULT NULL,
    `gradient_css` VARCHAR(120) DEFAULT 'linear-gradient(135deg,#1B2A6B,#1CB8D6)',
    `icon_class` VARCHAR(60) DEFAULT 'bi-camera',
    `featured` TINYINT(1) DEFAULT 0,
    `sort_order` SMALLINT UNSIGNED DEFAULT 0,
    `published` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category`),
    INDEX `idx_featured` (`featured`),
    INDEX `idx_published`(`published`),
    -- Composite index for getFeatured(): WHERE published=1 ORDER BY featured DESC, sort_order ASC
    -- MySQL can satisfy the WHERE + ORDER BY entirely from this index.
    INDEX `idx_pub_feat_sort` (`published`, `featured`, `sort_order`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- ── Team members ──────────────────────────────────────────────────────────────
  CREATE TABLE IF NOT EXISTS `team_members` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `role` VARCHAR(200) NOT NULL,
    `bio_1` TEXT NOT NULL,
    `bio_2` TEXT DEFAULT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `gradient_css` VARCHAR(120) DEFAULT 'linear-gradient(160deg,#1CB8D6,#1B2A6B)',
    `fallback_icon` VARCHAR(60) DEFAULT 'bi-person-fill',
    `sort_order` SMALLINT UNSIGNED DEFAULT 0,
    `published` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP -- (published, sort_order) for getAll/getPublished: WHERE published=1 ORDER BY sort_order,id
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- ── Team skills (child of team_members) ──────────────────────────────────────
  CREATE TABLE IF NOT EXISTS `team_skills` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `member_id` INT UNSIGNED NOT NULL,
    `skill_name` VARCHAR(80) NOT NULL,
    `sort_order` TINYINT UNSIGNED DEFAULT 0,
    FOREIGN KEY (`member_id`) REFERENCES `team_members`(`id`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- ── Home gallery images ───────────────────────────────────────────────────────
  CREATE TABLE IF NOT EXISTS `home_gallery` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `image_path` VARCHAR(255) NOT NULL,
    `alt_text` VARCHAR(255) NOT NULL,
    `is_wide` TINYINT(1) DEFAULT 0,
    `sort_order` TINYINT UNSIGNED DEFAULT 0,
    `published` TINYINT(1) DEFAULT 1
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- ── Site settings (key-value store) ─────────────────────────────────────────
  CREATE TABLE IF NOT EXISTS `site_settings` (
    `setting_key` VARCHAR(100) NOT NULL PRIMARY KEY,
    `setting_value` TEXT DEFAULT NULL,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
SET
  FOREIGN_KEY_CHECKS = 1;