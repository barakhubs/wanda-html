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
        // A web request completes in milliseconds — a live connection will never go stale
        // mid-request.  The old SELECT 1 health-check fired on every getInstance() call
        // (every model __construct), costing 4-6 extra round-trips per page load.
        // We connect once and reuse; if the handle truly dies, PDOException will surface
        // on the next real query just as it would have after the reconnect anyway.
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                // Persistent connections reuse an existing socket from the FPM
                // worker pool instead of doing TCP handshake + MySQL auth on every
                // request. Significant win when DB is on the same host.
                PDO::ATTR_PERSISTENT         => DB_PERSISTENT,
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
