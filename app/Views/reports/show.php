<?php
$date      = date('F j, Y', strtotime($report['created_at']));
$pdfUrl    = BASE_URL . '/' . $report['pdf_path'];
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
            <a href="<?= BASE_URL ?>/reports">Reports</a>
            <i class="bi bi-chevron-right"></i>
            <span><?= e($report['title']) ?></span>
        </div>
        <h1><?= e($report['title']) ?></h1>
        <p>
            <span style="opacity:.8"><i class="bi bi-calendar3"></i> <?= e($date) ?></span>
            &nbsp;&nbsp;
            <span class="blog-cat" style="position:static;display:inline-block;padding:.25rem .75rem">
                <?= e(ucfirst($report['category'])) ?>
            </span>
        </p>
    </div>
</section>

<!-- REPORT VIEWER -->
<section style="padding:3rem 0 5rem">
    <div class="container">

        <!-- Excerpt / summary -->
        <div style="max-width:760px;margin:0 auto 2rem">
            <p style="font-size:1.1rem;color:var(--text-muted);line-height:1.7">
                <?= e($report['excerpt']) ?>
            </p>
        </div>

        <!-- Inline PDF viewer (read-only, no forced download) -->
        <div style="max-width:960px;margin:0 auto;border-radius:12px;overflow:hidden;
                    box-shadow:0 4px 24px rgba(0,0,0,.12)">
            <iframe
                src="<?= e($pdfUrl) ?>#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
                style="width:100%;height:85vh;border:none;display:block"
                title="<?= e($report['title']) ?>"
                loading="lazy">
                <div style="padding:2rem;text-align:center">
                    <i class="bi bi-exclamation-triangle" style="font-size:2rem;color:#f59e0b"></i>
                    <p>Your browser does not support inline PDF viewing.</p>
                    <p>Please use a modern browser (Chrome, Edge, Firefox) to read this report.</p>
                </div>
            </iframe>
        </div>

        <!-- Back link -->
        <div style="text-align:center;margin-top:2.5rem">
            <a href="<?= BASE_URL ?>/reports" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> All Reports
            </a>
        </div>

    </div>
</section>
