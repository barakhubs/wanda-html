<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($pageDesc ?? setting('site_tagline', 'Wanda Communications Uganda — Your Strategic Partner In Achieving Communication Excellence.')) ?>">
    <title><?= e($pageTitle ?? setting('site_title', 'Wanda Communications Uganda')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/animations.css">
    <?= $extraHead ?? '' ?>
</head>

<body>

    <div class="nav-overlay" id="navOverlay"></div>

    <!-- ══════════════════════════════════════════
       TOPBAR
  ══════════════════════════════════════════ -->
    <div class="topbar">
        <div class="container">
            <div class="topbar-inner">
                <div class="topbar-contacts">
                    <?php
                    $topEmail  = setting('contact_email',   'Wandacommunicationsug@gmail.com');
                    $topPhone  = setting('contact_phone_1',  '+256 772 935 325');
                    ?>
                    <a href="mailto:<?= e($topEmail) ?>">
                        <i class="bi bi-envelope-fill"></i>
                        <?= e($topEmail) ?>
                    </a>
                    <a href="tel:<?= e(preg_replace('/\s+/', '', $topPhone)) ?>">
                        <i class="bi bi-telephone-fill"></i>
                        <?= e($topPhone) ?>
                    </a>
                </div>
                <div class="topbar-right">
                    <div class="topbar-social">
                        <?php
                        $sfb = setting('social_facebook',  '#');
                        $stw = setting('social_twitter',    '#');
                        $sig = setting('social_instagram',  '#');
                        $sli = setting('social_linkedin',   '#');
                        ?>
                        <a href="<?= e($sfb) ?>" aria-label="Facebook" <?= $sfb !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-facebook"></i></a>
                        <a href="<?= e($stw) ?>" aria-label="Twitter" <?= $stw !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-twitter-x"></i></a>
                        <a href="<?= e($sig) ?>" aria-label="Instagram" <?= $sig !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-instagram"></i></a>
                        <a href="<?= e($sli) ?>" aria-label="LinkedIn" <?= $sli !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-linkedin"></i></a>
                    </div>
                    <div class="topbar-divider"></div>
                    <a href="<?= BASE_URL ?>/contact" class="topbar-cta-link">
                        Free Consultation <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
       NAVBAR
  ══════════════════════════════════════════ -->
    <?php
    // $currentPage is set by each page before including this file.
    // Values: 'home' | 'blog' | 'portfolio' | 'team'
    $currentPage = $currentPage ?? '';
    function navActive(string $page, string $current): string
    {
        return $page === $current ? ' class="active"' : '';
    }
    ?>
    <nav class="navbar" id="navbar">
        <div class="container">
            <?php
            $headerLogoSrc = !empty(setting('logo_path'))
                ? BASE_URL . '/' . setting('logo_path')
                : BASE_URL . '/logo.jpg';
            $headerLogoAlt = setting('site_title', 'Wanda Communications Uganda');
            ?>
            <a href="<?= BASE_URL ?>/" class="nav-logo">
                <img
                    src="<?= e($headerLogoSrc) ?>"
                    alt="<?= e($headerLogoAlt) ?>"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="nav-logo-text" style="display:none">
                    <span class="logo-wanda">WANDA</span>
                    <span class="logo-comms">COMMUNICATIONS</span>
                </div>
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="<?= BASE_URL ?>/" <?= navActive('home', $currentPage) ?>>Home</a></li>
                <li><a href="<?= BASE_URL ?>/about" <?= navActive('about', $currentPage) ?>>About</a></li>
                <li><a href="<?= BASE_URL ?>/services" <?= navActive('services', $currentPage) ?>>Services</a></li>
                <li><a href="<?= BASE_URL ?>/team" <?= navActive('team', $currentPage) ?>>Team</a></li>
                <li><a href="<?= BASE_URL ?>/portfolio" <?= navActive('portfolio', $currentPage) ?>>Portfolio</a></li>
                <li><a href="<?= BASE_URL ?>/blog" <?= navActive('blog', $currentPage) ?>>Blog</a></li>
                <li>
                    <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-sm">Contact Us</a>
                </li>
            </ul>

            <button class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>