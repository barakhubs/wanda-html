<!-- PAGE HERO -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-geo1"></div>
    <div class="page-hero-geo2"></div>
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a>
            <i class="bi bi-chevron-right"></i>
            <span>Blog</span>
        </div>
        <h1>Insights &amp; Ideas</h1>
        <p>Perspectives on strategic communication, visual storytelling, advocacy, and development communication from our expert team.</p>
    </div>
</section>

<!-- BLOG SECTION -->
<section class="blog-section">
    <div class="container">

        <!-- Filter -->
        <div class="blog-filter">
            <button class="filter-btn active" data-filter="all">All Posts</button>
            <?php foreach ($categories as $cat) : ?>
                <button class="filter-btn" data-filter="<?= e($cat) ?>"><?= e(ucfirst($cat)) ?></button>
            <?php endforeach; ?>
        </div>

        <!-- Grid -->
        <?php if (empty($posts)) : ?>
            <div class="text-center" style="padding:4rem 0;color:var(--text-muted)">
                <i class="bi bi-pencil-square" style="font-size:3rem;display:block;margin-bottom:1rem"></i>
                <p>No blog posts yet. Check back soon!</p>
            </div>
        <?php else : ?>
            <div class="blog-grid">
                <?php foreach ($posts as $post) :
                    $thumbSrc = $post['thumbnail']
                        ? BASE_URL . '/' . $post['thumbnail']
                        : null;
                    $date = date('M j, Y', strtotime($post['created_at']));
                ?>
                    <article class="blog-card reveal" data-category="<?= e($post['category']) ?>">
                        <div class="blog-thumb" style="background:linear-gradient(135deg,var(--secondary),var(--primary))">
                            <?php if ($thumbSrc) : ?>
                                <img src="<?= e($thumbSrc) ?>" alt="<?= e($post['title']) ?>">
                            <?php endif; ?>
                            <span class="blog-cat"><?= e(ucfirst($post['category'])) ?></span>
                        </div>
                        <div class="blog-body">
                            <div class="blog-meta">
                                <span><i class="bi bi-calendar3"></i> <?= e($date) ?></span>
                                <span><i class="bi bi-clock"></i> <?= (int)$post['read_time'] ?> min read</span>
                            </div>
                            <h3><?= e($post['title']) ?></h3>
                            <p><?= e($post['excerpt']) ?></p>
                            <a href="<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>" class="read-more">
                                Read more <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (($pagination['total_pages'] ?? 1) > 1) : ?>
            <nav class="blog-pagination" aria-label="Blog page navigation">
                <?php if ($pagination['has_prev']) : ?>
                    <a href="?page=<?= $pagination['prev_page'] ?><?= $currentCat ? '&cat=' . urlencode($currentCat) : '' ?>"
                        class="btn btn-outline">&laquo; Previous</a>
                <?php endif; ?>
                <span class="pagination-info">
                    Page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?>
                </span>
                <?php if ($pagination['has_next']) : ?>
                    <a href="?page=<?= $pagination['next_page'] ?><?= $currentCat ? '&cat=' . urlencode($currentCat) : '' ?>"
                        class="btn btn-outline">Next &raquo;</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

    </div>
</section>

<!-- CTA -->
<section class="cta-strip">
    <div class="cta-strip-pattern"></div>
    <div class="container">
        <h2>Have a Communication Challenge?</h2>
        <p>Let's talk about how Wanda Communications can help you tell your story with impact.</p>
        <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-lg btn-pulse">
            Get In Touch <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>
<?= $extraScripts ?? '' ?>