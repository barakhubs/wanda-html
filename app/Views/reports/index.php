<!-- PAGE HERO -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-geo1"></div>
    <div class="page-hero-geo2"></div>
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a>
            <i class="bi bi-chevron-right"></i>
            <span>Reports</span>
        </div>
        <h1>Publications &amp; Reports</h1>
        <p>Research, policy briefs, evaluation reports, and advocacy publications from Wanda Communications Uganda.</p>
    </div>
</section>

<!-- REPORTS SECTION -->
<section class="blog-section">
    <div class="container">

        <!-- Category filter -->
        <div class="blog-filter">
            <a href="<?= BASE_URL ?>/reports"
                class="filter-btn<?= $activeCategory === '' ? ' active' : '' ?>">All</a>
            <?php foreach ($categories as $cat) : ?>
                <a href="<?= BASE_URL ?>/reports?category=<?= urlencode($cat) ?>"
                    class="filter-btn<?= $activeCategory === $cat ? ' active' : '' ?>">
                    <?= e(ucfirst($cat)) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($reports)) : ?>
            <div class="text-center" style="padding:4rem 0;color:var(--text-muted)">
                <i class="bi bi-file-earmark-text" style="font-size:3rem;display:block;margin-bottom:1rem"></i>
                <p>No reports published yet. Check back soon!</p>
            </div>
        <?php else : ?>
            <div class="blog-grid">
                <?php foreach ($reports as $report) :
                    $date = date('M j, Y', strtotime($report['created_at']));
                ?>
                    <article class="blog-card reveal">
                        <div class="blog-thumb">
                            <?php if (!empty($report['cover_path'])) : ?>
                                <img src="<?= BASE_URL . '/' . e($report['cover_path']) ?>"
                                    alt="<?= e($report['title']) ?>"
                                    style="width:100%;height:100%;object-fit:cover;display:block">
                            <?php else : ?>
                                <div style="background:linear-gradient(135deg,var(--secondary),var(--primary));display:flex;align-items:center;justify-content:center;height:100%">
                                    <i class="bi bi-file-earmark-pdf-fill"
                                        style="font-size:3.5rem;color:rgba(255,255,255,.85)"></i>
                                </div>
                            <?php endif; ?>
                            <span class="blog-cat"><?= e(ucfirst($report['category'])) ?></span>
                        </div>
                        <div class="blog-body">
                            <div class="blog-meta">
                                <span><i class="bi bi-calendar3"></i> <?= e($date) ?></span>
                            </div>
                            <h3 class="blog-title">
                                <a href="<?= BASE_URL ?>/reports/<?= e($report['slug']) ?>">
                                    <?= e($report['title']) ?>
                                </a>
                            </h3>
                            <p class="blog-excerpt"><?= e($report['excerpt']) ?></p>
                            <a href="<?= BASE_URL ?>/reports/<?= e($report['slug']) ?>"
                                class="blog-read-more">
                                Read Report <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- CTA STRIP -->
<section class="cta-strip">
    <div class="container">
        <div class="cta-strip-inner">
            <div class="cta-strip-text">
                <h2>Need a Custom Report?</h2>
                <p>We produce research, evaluation, and communication strategy reports tailored to your organisation.</p>
            </div>
            <a href="<?= BASE_URL ?>/contact" class="btn btn-white btn-lg">Get In Touch</a>
        </div>
    </div>
</section>