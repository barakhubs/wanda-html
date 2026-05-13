-- Migration 002: add cover_path to reports
-- Run once: mysql -u root wanda_db < database/migrations/002_add_cover_path_to_reports.sql
ALTER TABLE
  `reports`
ADD
  COLUMN `cover_path` VARCHAR(255) DEFAULT NULL
AFTER
  `pdf_path`;