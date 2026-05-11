<?php

/**
 * Database — PDO Singleton
 */
class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        // If we have an existing instance, verify it's still alive before returning it.
        // MySQL silently closes idle connections (wait_timeout), which causes the
        // singleton to hold a stale handle that fails on the next query.
        if (self::$instance !== null) {
            try {
                self::$instance->query('SELECT 1');
            } catch (PDOException $e) {
                // Connection is dead — drop it so we reconnect below.
                self::$instance = null;
            }
        }

        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                DB_HOST,
                DB_NAME,
                DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $attempts = 0;
            $lastError = null;

            while ($attempts < 3) {
                try {
                    self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
                    break; // Connected successfully.
                } catch (PDOException $e) {
                    $lastError = $e;
                    $attempts++;
                    if ($attempts < 3) {
                        usleep(100_000 * $attempts); // 100 ms, then 200 ms before retry.
                    }
                }
            }

            if (self::$instance === null) {
                error_log('Database connection failed after ' . $attempts . ' attempts: ' . $lastError->getMessage());
                die('<p style="font-family:sans-serif;color:#c00;padding:2rem;">
                    Database connection error. Please check configuration.</p>');
            }
        }

        return self::$instance;
    }
}
