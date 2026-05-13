<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminPageTitle ?? 'Admin') ?> | Wanda Admin</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/css/admin.css">
    <?= $adminExtraHead ?? '' ?>
</head>
<body>
<div class="admin-wrapper">

    <!-- SIDEBAR -->
    <?php
    $reqUri   = $_SERVER['REQUEST_URI'] ?? '';
    $isActive = function (string $path) use ($reqUri): bool {
        return str_contains($reqUri, $path);
    };
    ?>
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">Wanda <span>Admin</span></div>
        <nav class="admin-sidebar-nav">
            <div class="admin-nav-section-title">Overview</div>
            <a href="<?= BASE_URL ?>/admin" class="admin-nav-link <?= preg_match('#/admin/?$#', $reqUri) ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <div class="admin-nav-section-title">Content</div>
            <a href="<?= BASE_URL ?>/admin/blog"         class="admin-nav-link <?= $isActive('/admin/blog')         ? 'active' : '' ?>"><i class="bi bi-journal-text"></i> Blog Posts</a>
            <a href="<?= BASE_URL ?>/admin/portfolio"    class="admin-nav-link <?= $isActive('/admin/portfolio')    ? 'active' : '' ?>"><i class="bi bi-images"></i> Portfolio</a>
            <a href="<?= BASE_URL ?>/admin/team"         class="admin-nav-link <?= $isActive('/admin/team')         ? 'active' : '' ?>"><i class="bi bi-people"></i> Team Members</a>
            <a href="<?= BASE_URL ?>/admin/testimonials" class="admin-nav-link <?= $isActive('/admin/testimonials') ? 'active' : '' ?>"><i class="bi bi-chat-quote"></i> Testimonials</a>
            <a href="<?= BASE_URL ?>/admin/gallery"      class="admin-nav-link <?= $isActive('/admin/gallery')      ? 'active' : '' ?>"><i class="bi bi-grid"></i> Home Gallery</a>
            <a href="<?= BASE_URL ?>/admin/reports"     class="admin-nav-link <?= $isActive('/admin/reports')     ? 'active' : '' ?>"><i class="bi bi-file-earmark-text"></i> Reports</a>
            <div class="admin-nav-section-title">Site</div>
            <a href="<?= BASE_URL ?>/admin/settings" class="admin-nav-link <?= $isActive('/admin/settings') ? 'active' : '' ?>"><i class="bi bi-gear"></i> Settings</a>
            <a href="<?= BASE_URL ?>/" target="_blank" class="admin-nav-link"><i class="bi bi-box-arrow-up-right"></i> View Website</a>
        </nav>
        <div class="admin-sidebar-foot">
            <a href="<?= BASE_URL ?>/admin/logout"><i class="bi bi-box-arrow-right"></i> Log out</a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <?= $content ?>

</div><!-- .admin-wrapper -->
<?= $adminExtraScripts ?? '' ?>
</body>
</html>
