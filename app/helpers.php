<?php

/**
 * app/helpers.php — Global helper functions.
 *
 * Loaded by Composer's "files" autoloader so every request has access to
 * these utilities without manual require statements.
 */

// ── Output escaping ───────────────────────────────────────────────────────────

/**
 * HTML-encode a string for safe output in views.
 */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ── Site settings ─────────────────────────────────────────────────────────────

/**
 * Retrieve a site setting by key, with an optional default.
 * Returns '' (not the default) when the key exists but holds an empty string.
 */
function setting(string $key, string $default = ''): string
{
    static $cache = null;

    if ($cache === null) {
        try {
            $db    = \Database::getInstance();
            $rows  = $db->query('SELECT `setting_key`, `setting_value` FROM site_settings')->fetchAll(\PDO::FETCH_KEY_PAIR);
            $cache = $rows ?: [];
        } catch (\Throwable) {
            $cache = [];
        }
    }

    return array_key_exists($key, $cache) ? (string) $cache[$key] : $default;
}

// ── CSRF protection ───────────────────────────────────────────────────────────

/**
 * Generate (or reuse) a CSRF token for the current session.
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render a hidden CSRF input field.
 */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

/**
 * Verify the CSRF token submitted with a POST request.
 * Terminates with a 403 response on failure.
 */
function verifyCsrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    $expected  = $_SESSION['csrf_token'] ?? '';

    if ($submitted === '' || !hash_equals($expected, $submitted)) {
        http_response_code(403);
        exit('403 — CSRF token mismatch.');
    }
}

// ── Flash messages ────────────────────────────────────────────────────────────

/**
 * Store a flash message in the session.
 *
 * @param string $type    'success' | 'error' | 'warning' | 'info'
 * @param string $message Human-readable message text.
 */
function flashMessage(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Read and clear the stored flash message. Returns null when none is set.
 *
 * @return array{type: string, message: string}|null
 */
function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

// ── Slug generation ───────────────────────────────────────────────────────────

/**
 * Convert a title string into a URL-safe slug.
 */
function generateSlug(string $title): string
{
    $slug = mb_strtolower(trim($title));
    $slug = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}

/**
 * Ensure a slug is unique within the given table/column.
 * Appends -2, -3, … until a free slot is found.
 *
 * @param \PDO   $db      Active PDO connection.
 * @param string $slug    Desired slug.
 * @param string $table   Table name (validated against an allowlist).
 * @param string $column  Column to check (default 'slug').
 * @param int    $exclude Row ID to exclude (for updates, 0 = none).
 */
function uniqueSlug(\PDO $db, string $slug, string $table, int $exclude = 0, string $column = 'slug'): string
{
    // Allowlist to prevent SQL injection via dynamic table/column name
    $allowedTables  = ['blog_posts', 'portfolio_items', 'reports'];
    $allowedColumns = ['slug'];
    if (!in_array($table, $allowedTables, true) || !in_array($column, $allowedColumns, true)) {
        throw new \InvalidArgumentException("uniqueSlug: disallowed table or column.");
    }

    $base    = $slug;
    $counter = 2;

    while (true) {
        $sql  = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :slug";
        $bind = [':slug' => $slug];

        if ($exclude > 0) {
            $sql  .= ' AND id != :id';
            $bind[':id'] = $exclude;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($bind);
        $count = (int) $stmt->fetchColumn();

        if ($count === 0) {
            return $slug;
        }

        $slug = $base . '-' . $counter++;
    }
}

// ── File uploads ──────────────────────────────────────────────────────────────

/**
 * Validate and move an uploaded file to the appropriate uploads sub-folder.
 *
 * @param  array  $file       Entry from $_FILES (single file, not multi-upload).
 * @param  string $subfolder  Sub-folder inside public/uploads/ (e.g. 'blog', 'team').
 * @return string             Relative path from the project root, e.g. 'uploads/blog/abc.webp'.
 * @throws \RuntimeException  On any validation or I/O failure.
 */
function handleUpload(array $file, string $subfolder): string
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        $msgs = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds the server upload limit.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds the form size limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing server temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'Upload blocked by a PHP extension.',
        ];
        throw new \RuntimeException($msgs[$file['error']] ?? 'Upload error code ' . $file['error']);
    }

    if ($file['size'] > MAX_UPLOAD_BYTES) {
        throw new \RuntimeException('File is too large (max ' . (MAX_UPLOAD_BYTES / 1024 / 1024) . ' MB).');
    }

    // Verify MIME type using finfo (do NOT trust $_FILES['type'])
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);

    // PDF-only subfolder
    if ($subfolder === 'reports') {
        if ($mime !== 'application/pdf') {
            throw new \RuntimeException('Only PDF files are accepted for reports.');
        }
        $ext = 'pdf';
    } else {
        if (!in_array($mime, ALLOWED_MIME_TYPES, true)) {
            throw new \RuntimeException('Invalid file type. Allowed: JPEG, PNG, WebP, GIF.');
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
            throw new \RuntimeException('Invalid file extension.');
        }
    }

    // Allowlist subfolder names to prevent path traversal
    $allowed = ['blog', 'portfolio', 'team', 'gallery', 'logos', 'reports'];
    if (!in_array($subfolder, $allowed, true)) {
        throw new \RuntimeException('Invalid upload subfolder.');
    }

    $dir = UPLOAD_PATH . $subfolder . '/';
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new \RuntimeException('Could not create upload directory.');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $dest     = $dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new \RuntimeException('Could not save uploaded file.');
    }

    return 'uploads/' . $subfolder . '/' . $filename;
}

// ── Pagination ───────────────────────────────────────────────────────────────

/**
 * Build pagination metadata for use in views.
 *
 * @param int $total       Total number of items.
 * @param int $perPage     Items per page.
 * @param int $currentPage The current (1-based) page number.
 * @return array{total:int, per_page:int, current_page:int, total_pages:int,
 *               has_prev:bool, has_next:bool, prev_page:int, next_page:int}
 */
function paginate(int $total, int $perPage, int $currentPage): array
{
    $perPage    = max(1, $perPage);
    $totalPages = (int) ceil($total / $perPage);
    $totalPages = max(1, $totalPages);
    $current    = max(1, min($currentPage, $totalPages));

    return [
        'total'        => $total,
        'per_page'     => $perPage,
        'current_page' => $current,
        'total_pages'  => $totalPages,
        'has_prev'     => $current > 1,
        'has_next'     => $current < $totalPages,
        'prev_page'    => $current - 1,
        'next_page'    => $current + 1,
    ];
}

/**
 * Delete a previously uploaded file.
 * Silently ignores missing files.
 *
 * @param string $relativePath  Path relative to project root, e.g. 'uploads/blog/abc.jpg'.
 */
function deleteUpload(string $relativePath): void
{
    if ($relativePath === '') {
        return;
    }
    $abs = ROOT_PATH . '/public/' . ltrim($relativePath, '/');
    if (is_file($abs)) {
        @unlink($abs);
    }
}
