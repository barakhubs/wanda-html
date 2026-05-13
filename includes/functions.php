<?php

/**
 * Shared helper functions
 * Wanda Communications Uganda
 */

// ── Output escaping ───────────────────────────────────────────────────────────
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ── Slug generation ───────────────────────────────────────────────────────────
function generateSlug(string $text): string
{
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
    $text = preg_replace('/[\s\-]+/', '-', $text);
    return trim($text, '-');
}

// Ensure a slug is unique in a given table/column
function uniqueSlug(PDO $db, string $slug, string $table, int $excludeId = 0): string
{
    $base  = $slug;
    $count = 1;
    // Prepare once outside the loop — re-executing a PDOStatement is far cheaper
    // than re-preparing on every collision iteration.
    $stmt  = $db->prepare("SELECT id FROM `{$table}` WHERE slug = ? AND id != ?");

    do {
        $stmt->execute([$slug, $excludeId]);
        $exists = (bool) $stmt->fetchColumn();

        if ($exists) {
            $slug = $base . '-' . $count++;
        }
    } while ($exists);

    return $slug;
}

// ── CSRF protection ───────────────────────────────────────────────────────────
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrfToken(), $token)) {
        http_response_code(403);
        die('<p>Invalid CSRF token. Go back and try again.</p>');
    }
}

// ── File upload ───────────────────────────────────────────────────────────────
/**
 * Handle a validated image upload.
 *
 * @param  array  $file       $_FILES element
 * @param  string $subDir     Subdirectory under uploads/ (blog|portfolio|team)
 * @return string             Relative path stored in DB  e.g. "uploads/blog/abc.jpg"
 * @throws RuntimeException   On validation failure
 */
function handleUpload(array $file, string $subDir): string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload error code: ' . $file['error']);
    }

    if ($file['size'] > MAX_UPLOAD_BYTES) {
        throw new RuntimeException('File exceeds maximum allowed size (5 MB).');
    }

    // Validate MIME via getimagesize (reads file header, not extension)
    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        throw new RuntimeException('Uploaded file is not a valid image.');
    }

    $mime = $info['mime'];
    if (!in_array($mime, ALLOWED_MIME_TYPES, true)) {
        throw new RuntimeException('Image type not allowed: ' . $mime);
    }

    // Static: allocated once per PHP process lifetime rather than on every upload call.
    static $extMap = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    $ext = $extMap[$mime];

    // Random filename
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $destDir  = UPLOAD_PATH . $subDir . '/';
    $destPath = $destDir . $filename;

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Could not save uploaded file.');
    }

    return 'uploads/' . $subDir . '/' . $filename;
}

// ── Delete uploaded file ──────────────────────────────────────────────────────
function deleteUpload(string $relativePath): void
{
    if (empty($relativePath)) return;

    // Only delete files inside uploads/ to prevent path traversal
    $safe = ROOT_PATH . '/' . ltrim($relativePath, '/');
    $real = realpath($safe);

    if ($real && str_starts_with($real, ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads')) {
        @unlink($real);
    }
}

// ── Pagination helper ─────────────────────────────────────────────────────────
function paginate(int $total, int $perPage, int $current): array
{
    $pages = (int) ceil($total / $perPage);
    $offset = ($current - 1) * $perPage;
    return ['pages' => $pages, 'offset' => $offset];
}

// ── Site settings helper ──────────────────────────────────────────────────────
/**
 * Retrieve a site setting by key, with optional fallback default.
 * Uses a per-request static SiteSettings instance.
 *
 * Example:  setting('site_title', 'Wanda Communications Uganda')
 */
function setting(string $key, string $default = ''): string
{
    static $settings = null;
    if ($settings === null) {
        $settings = new SiteSettings();
    }
    return $settings->get($key, $default);
}

// ── Flash messages (admin) ────────────────────────────────────────────────────
function flashMessage(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
