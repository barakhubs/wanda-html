<?php
require_once __DIR__ . '/includes/bootstrap.php';

// Route: /blog/{slug}  →  blog-post.php?slug={slug}
$slug = trim($_GET['slug'] ?? '');
if (empty($slug) || !preg_match('/^[a-z0-9\-]+$/', $slug)) {
    http_response_code(404);
    header('Location: ' . BASE_URL . '/blog');
    exit;
}

$blogModel = new BlogPost();
$post      = $blogModel->getBySlug($slug);

if (!$post) {
    http_response_code(404);
    $pageTitle   = '404 — Post Not Found | Wanda Communications';
    $pageDesc    = '';
    $currentPage = 'blog';
    require_once __DIR__ . '/includes/header.php';
    echo '<section style="padding:8rem 0;text-align:center">
            <div class="container">
              <h1 style="font-size:4rem;color:var(--primary)">404</h1>
              <p>This blog post was not found.</p>
              <a href="' . BASE_URL . '/blog" class="btn btn-primary">Back to Blog</a>
            </div>
          </section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle   = $post['title'] . ' | Wanda Communications Uganda';
$pageDesc    = $post['excerpt'];  // header.php applies e() — do not pre-escape here
$currentPage = 'blog';

$thumbSrc      = $post['thumbnail'] ? BASE_URL . '/' . $post['thumbnail'] : null;
$dateFormatted = date('F j, Y', strtotime($post['created_at']));

// Sidebar: SQL-level exclusion of the current post; no PHP array_filter needed.
$recentPosts = $blogModel->getSidebarPosts($post['id'], 4);

require_once __DIR__ . '/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-geo1"></div>
    <div class="page-hero-geo2"></div>
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a>
            <i class="bi bi-chevron-right"></i>
            <a href="<?= BASE_URL ?>/blog">Blog</a>
            <i class="bi bi-chevron-right"></i>
            <span><?= e($post['title']) ?></span>
        </div>
        <h1><?= e($post['title']) ?></h1>
        <div class="breadcrumb" style="margin-top:.75rem;gap:1.5rem">
            <span><i class="bi bi-calendar3"></i> <?= e($dateFormatted) ?></span>
            <span><i class="bi bi-clock"></i> <?= (int)$post['read_time'] ?> min read</span>
            <span class="blog-cat" style="position:static;border-radius:999px;padding:.2em .8em;font-size:.8rem">
                <?= e(ucfirst($post['category'])) ?>
            </span>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
       SINGLE POST CONTENT
  ══════════════════════════════════════════ -->
<section style="padding:4rem 0;background:var(--bg-light)">
    <div class="container">
        <div class="post-layout">

            <!-- Article body -->
            <article>
                <?php if ($thumbSrc) : ?>
                    <img src="<?= e($thumbSrc) ?>" alt="<?= e($post['title']) ?>"
                        style="width:100%;height:360px;object-fit:cover;border-radius:var(--radius-md);margin-bottom:2rem;box-shadow:var(--shadow-md)">
                <?php endif; ?>

                <div class="post-body" style="line-height:1.9;font-size:1.05rem;color:var(--text)">
                    <?= $post['body'] /* Body is stored as trusted HTML from admin */ ?>
                </div>

                <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
                    <a href="<?= BASE_URL ?>/blog" class="btn btn-outline">
                        <i class="bi bi-arrow-left"></i> Back to Blog
                    </a>
                    <a href="<?= BASE_URL ?>/contact" class="btn btn-primary">
                        Work With Us <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </article>

            <!-- Sidebar -->
            <aside>
                <?php if (!empty($recentPosts)) : ?>
                    <div class="service-card" style="margin-bottom:1.5rem">
                        <h4 style="margin-bottom:1rem;color:var(--secondary)">Recent Posts</h4>
                        <?php foreach ($recentPosts as $rp) : ?>
                            <a href="<?= BASE_URL ?>/blog/<?= e($rp['slug']) ?>"
                                style="display:block;padding:.75rem 0;border-bottom:1px solid var(--border);text-decoration:none;color:var(--text);font-weight:600;font-size:.9rem;line-height:1.4">
                                <?= e($rp['title']) ?>
                                <span style="display:block;font-size:.78rem;color:var(--text-muted);font-weight:400;margin-top:.25rem">
                                    <?= date('M j, Y', strtotime($rp['created_at'])) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="service-card" style="text-align:center;background:var(--secondary);color:#fff;padding:2rem">
                    <i class="bi bi-chat-dots-fill" style="font-size:2.5rem;color:var(--primary);display:block;margin-bottom:1rem"></i>
                    <h4 style="color:#fff;margin-bottom:.75rem">Have a Project?</h4>
                    <p style="color:rgba(255,255,255,.75);font-size:.9rem;margin-bottom:1.5rem">Let's discuss how we can help you communicate your impact.</p>
                    <a href="<?= BASE_URL ?>/contact" class="btn btn-primary" style="width:100%">Get In Touch</a>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>