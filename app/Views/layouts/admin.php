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
    <!-- Skip navigation for keyboard / screen-reader users -->
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <?php
        $reqUri   = $_SERVER['REQUEST_URI'] ?? '';
        $isActive = function (string $path) use ($reqUri): bool {
            return str_contains($reqUri, $path);
        };
        $navLink = function (string $href, string $label, string $icon, bool $active) use ($reqUri): string {
            $cls  = $active ? 'admin-nav-link active' : 'admin-nav-link';
            $aria = $active ? ' aria-current="page"' : '';
            return "<a href=\"{$href}\" class=\"{$cls}\"{$aria}><i class=\"bi {$icon}\" aria-hidden=\"true\"></i> {$label}</a>";
        };
        ?>
        <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin navigation">
            <div class="admin-sidebar-brand" aria-hidden="true">Wanda <span>Admin</span></div>
            <nav class="admin-sidebar-nav" aria-label="Main menu">
                <div class="admin-nav-section-title" aria-hidden="true">Overview</div>
                <?= $navLink(BASE_URL . '/admin',            'Dashboard',    'bi-speedometer2',         (bool)preg_match('#/admin/?$#', $reqUri)) ?>
                <div class="admin-nav-section-title" aria-hidden="true">Content</div>
                <?= $navLink(BASE_URL . '/admin/blog',         'Blog Posts',   'bi-journal-text',         $isActive('/admin/blog')) ?>
                <?= $navLink(BASE_URL . '/admin/portfolio',    'Portfolio',    'bi-images',               $isActive('/admin/portfolio')) ?>
                <?= $navLink(BASE_URL . '/admin/team',         'Team Members', 'bi-people',               $isActive('/admin/team')) ?>
                <?= $navLink(BASE_URL . '/admin/testimonials', 'Testimonials', 'bi-chat-quote',           $isActive('/admin/testimonials')) ?>
                <?= $navLink(BASE_URL . '/admin/gallery',      'Home Gallery', 'bi-grid',                 $isActive('/admin/gallery')) ?>
                <?= $navLink(BASE_URL . '/admin/reports',      'Reports',      'bi-file-earmark-text',    $isActive('/admin/reports')) ?>
                <div class="admin-nav-section-title" aria-hidden="true">Site</div>
                <?= $navLink(BASE_URL . '/admin/settings',     'Settings',     'bi-gear',                 $isActive('/admin/settings')) ?>
                <a href="<?= BASE_URL ?>/" target="_blank" rel="noopener" class="admin-nav-link" aria-label="View website (opens in new tab)">
                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> View Website
                </a>
            </nav>
            <div class="admin-sidebar-foot">
                <a href="<?= BASE_URL ?>/admin/logout"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Log out</a>
            </div>
        </aside>

        <!-- Mobile backdrop overlay -->
        <div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>

        <!-- MAIN CONTENT -->
        <!-- Accessible skip-link target (zero-size, focusable) -->
        <span id="main-content" tabindex="-1" aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden;outline:none"></span>
        <?= $content ?>

    </div><!-- .admin-wrapper -->
    <?= $adminExtraScripts ?? '' ?>
    <script>
        /* ── Admin UI: mobile sidebar + accessibility ── */
        (function() {
            'use strict';

            var sidebar = document.getElementById('admin-sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var toggleBtns = [];

            /* ── Inject hamburger button into every .admin-topbar ── */
            document.querySelectorAll('.admin-topbar').forEach(function(topbar) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'sidebar-toggle-btn';
                btn.setAttribute('aria-label', 'Open navigation menu');
                btn.setAttribute('aria-expanded', 'false');
                btn.setAttribute('aria-controls', 'admin-sidebar');
                btn.innerHTML = '<i class="bi bi-list" aria-hidden="true"></i>';
                btn.addEventListener('click', openSidebar);
                topbar.insertBefore(btn, topbar.firstChild);
                toggleBtns.push(btn);
            });

            function openSidebar() {
                sidebar.classList.add('sidebar-open');
                overlay.classList.add('is-visible');
                overlay.setAttribute('aria-hidden', 'false');
                document.body.classList.add('sidebar-is-open');
                toggleBtns.forEach(function(b) {
                    b.setAttribute('aria-expanded', 'true');
                    b.setAttribute('aria-label', 'Close navigation menu');
                    b.innerHTML = '<i class="bi bi-x-lg" aria-hidden="true"></i>';
                });
                /* Move focus to first nav link for keyboard users */
                var firstLink = sidebar.querySelector('.admin-nav-link');
                if (firstLink) firstLink.focus();
            }

            function closeSidebar() {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('is-visible');
                overlay.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('sidebar-is-open');
                toggleBtns.forEach(function(b) {
                    b.setAttribute('aria-expanded', 'false');
                    b.setAttribute('aria-label', 'Open navigation menu');
                    b.innerHTML = '<i class="bi bi-list" aria-hidden="true"></i>';
                });
            }

            /* Close on overlay click */
            overlay.addEventListener('click', closeSidebar);

            /* Close on Escape key */
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('sidebar-open')) {
                    closeSidebar();
                    if (toggleBtns[0]) toggleBtns[0].focus();
                }
            });

            /* Close when nav link is tapped on mobile */
            sidebar.querySelectorAll('.admin-nav-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) closeSidebar();
                });
            });

            /* Keep sidebar visible when resizing to desktop */
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) closeSidebar();
            });

            /* ── Responsive tables: inject data-label & class ── */
            document.querySelectorAll('.admin-table-wrap').forEach(function(wrap) {
                var table = wrap.querySelector('.admin-table');
                if (!table) return;
                var headers = Array.prototype.map.call(
                    table.querySelectorAll('thead th'),
                    function(th) {
                        return th.textContent.trim();
                    }
                );
                table.querySelectorAll('tbody tr').forEach(function(row) {
                    Array.prototype.forEach.call(row.querySelectorAll('td'), function(td, i) {
                        if (headers[i]) td.setAttribute('data-label', headers[i]);
                    });
                });
                wrap.classList.add('responsive-table');
            });

            /* ── Auto-wrap topbar button text in .btn-label for responsive hiding ── */
            document.querySelectorAll('.admin-topbar-actions .btn-adm').forEach(function(btn) {
                Array.prototype.forEach.call(btn.childNodes, function(node) {
                    /* Wrap bare text nodes (not already-wrapped spans/icons) */
                    if (node.nodeType === 3 && node.textContent.trim()) {
                        var span = document.createElement('span');
                        span.className = 'btn-label';
                        span.textContent = node.textContent;
                        btn.replaceChild(span, node);
                    }
                });
            });

            /* ── Accessible alerts: live region ── */
            document.querySelectorAll('.alert').forEach(function(el) {
                if (!el.getAttribute('role')) el.setAttribute('role', 'alert');
                if (!el.getAttribute('aria-live')) el.setAttribute('aria-live', 'polite');
            });
        })();
    </script>
</body>

</html>