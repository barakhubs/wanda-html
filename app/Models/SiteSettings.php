<?php

namespace App\Models;

class SiteSettings
{
    private \PDO $db;
    private array $cache = [];

    public function __construct()
    {
        $this->db = \Database::getInstance();
    }

    public function getAll(): array
    {
        if (empty($this->cache)) {
            try {
                $stmt = $this->db->query(
                    'SELECT setting_key, setting_value FROM site_settings'
                );
                $this->cache = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
            } catch (\PDOException $e) {
                error_log('SiteSettings::getAll() — ' . $e->getMessage());
                $this->cache = [];
            }
        }
        return $this->cache;
    }

    public function get(string $key, string $default = ''): string
    {
        $all = $this->getAll();
        return isset($all[$key]) && $all[$key] !== '' ? $all[$key] : $default;
    }

    public function setMany(array $data): void
    {
        $filtered = array_filter($data, static fn($v) => $v !== null);
        if (empty($filtered)) return;

        $placeholders = implode(',', array_fill(0, count($filtered), '(?,?)'));
        $values = [];
        foreach ($filtered as $key => $value) {
            $values[] = $key;
            $values[] = (string)$value;
        }

        $this->db->prepare(
            "INSERT INTO site_settings (setting_key, setting_value)
             VALUES {$placeholders}
             ON DUPLICATE KEY UPDATE
               setting_value = VALUES(setting_value),
               updated_at    = CURRENT_TIMESTAMP"
        )->execute($values);

        $this->cache = [];
    }

    public static function ensureTable(\PDO $db): void
    {
        $db->exec(
            'CREATE TABLE IF NOT EXISTS `site_settings` (
               `setting_key`   VARCHAR(100) NOT NULL PRIMARY KEY,
               `setting_value` TEXT         DEFAULT NULL,
               `updated_at`    DATETIME     DEFAULT CURRENT_TIMESTAMP
                               ON UPDATE CURRENT_TIMESTAMP
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }
}
