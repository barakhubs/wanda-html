<?php
require_once __DIR__ . '/auth.php';

$blogModel        = new BlogPost();
$portfolioModel   = new Portfolio();
$teamModel        = new TeamMember();
$testimonialModel = new Testimonial();
$galleryModel     = new HomeGallery();

$counts = [
    'blog'         => $blogModel->count(),
    'portfolio'    => $portfolioModel->count(),
    'team'         => $teamModel->count(),
    'testimonials' => $testimonialModel->count(),
];

// Recent blog posts (5) — uses LIMIT query instead of fetching the whole table
$recentPosts = $blogModel->getRecent(5);

$adminPageTitle = 'Dashboard';
$flash = getFlash();
require_once ROOT_PATH . '/admin/partials/head.php';
require_once ROOT_PATH . '/admin/partials/sidebar.php';
?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <div class="admin-topbar-actions">
            <span style="font-size:.85rem;color:var(--admin-muted)">
                Logged in as <strong><?= e($_SESSION['admin_username'] ?? 'Admin') ?></strong>
            </span>
        </div>
    </div>
    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="admin-stats">
            <div class="admin-stat-card">
                <div class="admin-stat-icon blue"><i class="bi bi-journal-text"></i></div>
                <div>
                    <div class="admin-stat-num"><?= $counts['blog'] ?></div>
                    <div class="admin-stat-label">Blog Posts</div>
                </div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-icon green"><i class="bi bi-images"></i></div>
                <div>
                    <div class="admin-stat-num"><?= $counts['portfolio'] ?></div>
                    <div class="admin-stat-label">Portfolio Items</div>
                </div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-icon amber"><i class="bi bi-people"></i></div>
                <div>
                    <div class="admin-stat-num"><?= $counts['team'] ?></div>
                    <div class="admin-stat-label">Team Members</div>
                </div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-icon purple"><i class="bi bi-chat-quote"></i></div>
                <div>
                    <div class="admin-stat-num"><?= $counts['testimonials'] ?></div>
                    <div class="admin-stat-label">Testimonials</div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="admin-card">
            <div class="admin-card-title">Quick Actions</div>
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
                <a href="<?= BASE_URL ?>/admin/blog/create.php" class="btn-adm btn-adm-primary">
                    <i class="bi bi-plus-lg"></i> New Blog Post
                </a>
                <a href="<?= BASE_URL ?>/admin/portfolio/create.php" class="btn-adm btn-adm-success">
                    <i class="bi bi-plus-lg"></i> New Portfolio Item
                </a>
                <a href="<?= BASE_URL ?>/admin/team/create.php" class="btn-adm btn-adm-outline">
                    <i class="bi bi-plus-lg"></i> Add Team Member
                </a>
                <a href="<?= BASE_URL ?>/admin/gallery/create.php" class="btn-adm btn-adm-outline">
                    <i class="bi bi-cloud-upload"></i> Upload Gallery Image
                </a>
            </div>
        </div>

        <!-- Recent Blog Posts -->
        <div class="admin-card">
            <div class="admin-card-title">
                Recent Blog Posts
                <a href="<?= BASE_URL ?>/admin/blog/" class="btn-adm btn-adm-outline btn-adm-sm">View all</a>
            </div>
            <?php if (empty($recentPosts)) : ?>
                <p style="color:var(--admin-muted);font-size:.9rem">No blog posts yet.
                    <a href="<?= BASE_URL ?>/admin/blog/create.php">Create one now.</a>
                </p>
            <?php else : ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPosts as $post) : ?>
                                <tr>
                                    <td><?= e($post['title']) ?></td>
                                    <td><span class="badge badge-primary"><?= e(ucfirst($post['category'])) ?></span></td>
                                    <td>
                                        <?php if ($post['published']) : ?>
                                            <span class="badge badge-success">Published</span>
                                        <?php else : ?>
                                            <span class="badge badge-muted">Draft</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e(date('d M Y', strtotime($post['created_at']))) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/admin/blog/edit.php?id=<?= $post['id'] ?>" class="btn-adm btn-adm-outline btn-adm-sm"><i class="bi bi-pencil"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div><!-- .admin-content -->
</div><!-- .admin-main -->

<?php require_once ROOT_PATH . '/admin/partials/foot.php'; ?>