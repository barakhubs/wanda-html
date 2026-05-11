<?php

/**
 * SiteSettings — manages key-value site configuration stored in the database.
 *
 * Table: site_settings (setting_key PK, setting_value TEXT, updated_at DATETIME)
 *
 * Usage:
 *   $s = new SiteSettings();
 *   $s->get('site_title', 'Wanda Communications');
 *   $s->setMany(['site_title' => 'New Title', 'site_tagline' => '...']);
 *   SiteSettings::ensureTable(Database::getInstance()); // run once for upgrades
 */
class SiteSettings
{
    private PDO $db;
    private array $cache = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Read ──────────────────────────────────────────────────────────────────

    /**
     * Return all settings as an associative array [key => value].
     * Results are cached per-request.
     */
    public function getAll(): array
    {
        if (empty($this->cache)) {
            try {
                $stmt = $this->db->query(
                    'SELECT setting_key, setting_value FROM site_settings'
                );
                $this->cache = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            } catch (PDOException $e) {
                // Table may not exist yet on first install — return empty
                error_log('SiteSettings::getAll() — ' . $e->getMessage());
                $this->cache = [];
            }
        }

        return $this->cache;
    }

    /**
     * Get a single setting value, falling back to $default if not set.
     */
    public function get(string $key, string $default = ''): string
    {
        $all = $this->getAll();
        return isset($all[$key]) && $all[$key] !== '' ? $all[$key] : $default;
    }

    // ── Write ─────────────────────────────────────────────────────────────────

    /**
     * Upsert multiple settings at once.
     * Passing null as a value skips that key (e.g. file upload not provided).
     */
    public function setMany(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO site_settings (setting_key, setting_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE
               setting_value = VALUES(setting_value),
               updated_at    = CURRENT_TIMESTAMP'
        );

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $stmt->execute([$key, (string) $value]);
            }
        }

        $this->cache = []; // invalidate cache
    }

    // ── Schema helper ─────────────────────────────────────────────────────────

    /**
     * Create the site_settings table if it does not already exist.
     * Call once from the admin settings page for graceful upgrades.
     */
    public static function ensureTable(PDO $db): void
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
