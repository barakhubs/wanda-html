-- Migration: create reports table
-- Run once: mysql -u root wanda_db < database/migrations/001_create_reports_table.sql

CREATE TABLE IF NOT EXISTS `reports` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title`      VARCHAR(255)                          NOT NULL,
    `slug`       VARCHAR(255)                          NOT NULL UNIQUE,
    `category`   VARCHAR(80)                           NOT NULL DEFAULT 'research',
    `excerpt`    TEXT                                  NOT NULL,
    `pdf_path`   VARCHAR(255)                          NOT NULL,
    `published`  TINYINT(1)                            DEFAULT 0,
    `created_at` DATETIME                              DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME                              DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_slug`      (`slug`),
    INDEX `idx_published` (`published`),
    INDEX `idx_category`  (`category`),
    INDEX `idx_pub_date`  (`published`, `created_at`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
