<!-- PAGE HERO -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-geo1"></div>
    <div class="page-hero-geo2"></div>
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a>
            <i class="bi bi-chevron-right"></i>
            <span>Portfolio</span>
        </div>
        <h1>Our Portfolio</h1>
        <p>Showcasing impactful work across photography, videography, advocacy campaigns, and strategic communications across Uganda and East Africa.</p>
    </div>
</section>

<!-- PORTFOLIO SECTION -->
<section class="portfolio-full">
    <div class="container">

        <!-- Filter -->
        <div class="portfolio-filter">
            <button class="filter-btn active" data-filter="all">All Projects</button>
            <?php foreach ($categories as $cat) : ?>
                <button class="filter-btn" data-filter="<?= e($cat) ?>"><?= e(ucfirst($cat)) ?></button>
            <?php endforeach; ?>
        </div>

        <!-- Grid -->
        <?php if (empty($items)) : ?>
            <div class="text-center" style="padding:4rem 0;color:var(--text-muted)">
                <i class="bi bi-images" style="font-size:3rem;display:block;margin-bottom:1rem"></i>
                <p>Portfolio items coming soon!</p>
            </div>
        <?php else : ?>
            <div class="portfolio-grid">
                <?php foreach ($items as $item) :
                    $thumbSrc = $item['thumbnail']
                        ? BASE_URL . '/' . $item['thumbnail']
                        : null;
                ?>
                    <div class="portfolio-item"
                        data-category="<?= e($item['category']) ?>"
                        data-title="<?= e($item['title']) ?>"
                        data-desc="<?= e($item['full_desc']) ?>">
                        <div class="portfolio-thumb" style="background: <?= e($item['gradient_css']) ?>">
                            <?php if ($thumbSrc) : ?>
                                <img src="<?= e($thumbSrc) ?>" alt="<?= e($item['title']) ?>">
                            <?php endif; ?>
                            <i class="bi <?= e($item['icon_class']) ?> portfolio-thumb-icon"></i>
                            <span class="portfolio-category"><?= e(ucfirst($item['category'])) ?></span>
                            <div class="portfolio-overlay">
                                <div class="portfolio-overlay-icon"><i class="bi bi-zoom-in"></i></div>
                            </div>
                        </div>
                        <div class="portfolio-body">
                            <h3><?= e($item['title']) ?></h3>
                            <p><?= e($item['short_desc']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- CTA STRIP -->
<section class="cta-strip">
    <div class="container">
        <h2 class="reveal">Have a Project in Mind?</h2>
        <p class="reveal delay-1">Let us help you document your impact, build your brand, and tell your story with clarity and power.</p>
        <div class="reveal delay-2">
            <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-lg">
                Start Your Project <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- LIGHTBOX -->
<div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-label="Project details">
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose" aria-label="Close">&times;</button>
        <div class="lightbox-img">
            <i class="bi bi-images" style="font-size:4rem;color:rgba(255,255,255,.3)"></i>
        </div>
        <div class="lightbox-body">
            <span class="lightbox-cat" id="lbCategory"></span>
            <h3 id="lbTitle"></h3>
            <p id="lbDesc"></p>
        </div>
    </div>
</div>
<?= $extraScripts ?? '' ?>
