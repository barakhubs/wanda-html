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

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="container">
            <div class="topbar-inner">
                <div class="topbar-contacts">
                    <?php
                    $topEmail = setting('contact_email',  'Wandacommunicationsug@gmail.com');
                    $topPhone = setting('contact_phone_1', '+256 772 935 325');
                    ?>
                    <a href="mailto:<?= e($topEmail) ?>"><i class="bi bi-envelope-fill"></i> <?= e($topEmail) ?></a>
                    <a href="tel:<?= e(preg_replace('/\s+/', '', $topPhone)) ?>"><i class="bi bi-telephone-fill"></i> <?= e($topPhone) ?></a>
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
                    <a href="<?= BASE_URL ?>/contact" class="topbar-cta-link">Free Consultation <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- NAVBAR -->
    <?php
    $currentPage = $currentPage ?? '';
    $navActive = static function (string $page, string $current): string {
        return $page === $current ? ' class="active"' : '';
    };
    $logoPath      = setting('logo_path');
    $headerLogoSrc = $logoPath !== '' ? BASE_URL . '/' . $logoPath : BASE_URL . '/logo.jpg';
    $headerLogoAlt = setting('site_title', 'Wanda Communications Uganda');
    ?>
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="<?= BASE_URL ?>/" class="nav-logo">
                <img src="<?= e($headerLogoSrc) ?>" alt="<?= e($headerLogoAlt) ?>"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="nav-logo-text" style="display:none">
                    <span class="logo-wanda">WANDA</span>
                    <span class="logo-comms">COMMUNICATIONS</span>
                </div>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="<?= BASE_URL ?>/" <?= $navActive('home',      $currentPage) ?>>Home</a></li>
                <li><a href="<?= BASE_URL ?>/about" <?= $navActive('about',     $currentPage) ?>>About</a></li>
                <li><a href="<?= BASE_URL ?>/services" <?= $navActive('services',  $currentPage) ?>>Services</a></li>
                <li><a href="<?= BASE_URL ?>/team" <?= $navActive('team',      $currentPage) ?>>Team</a></li>
                <li><a href="<?= BASE_URL ?>/portfolio" <?= $navActive('portfolio', $currentPage) ?>>Portfolio</a></li>
                <li><a href="<?= BASE_URL ?>/reports" <?= $navActive('reports',   $currentPage) ?>>Reports</a></li>
                <li><a href="<?= BASE_URL ?>/blog" <?= $navActive('blog',      $currentPage) ?>>Blog</a></li>
                <li><a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-sm">Contact Us</a></li>
            </ul>
            <button class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <?= $content ?>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <?php
                    $footerLogoSrc = !empty(setting('footer_logo_path'))
                        ? BASE_URL . '/' . setting('footer_logo_path')
                        : BASE_URL . '/logo.jpg';
                    $footerLogoAlt = setting('site_title', 'Wanda Communications Uganda');
                    ?>
                    <div class="footer-logo-wrap">
                        <img src="<?= e($footerLogoSrc) ?>" alt="<?= e($footerLogoAlt) ?>"
                            class="footer-logo"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                        <div class="footer-logo-text" style="display:none">
                            <div class="fw">WANDA</div>
                            <div class="fc">COMMUNICATIONS UGANDA</div>
                        </div>
                    </div>
                    <p>Your strategic partner in achieving communication excellence. Delivering impactful development communication solutions across Uganda and East Africa since 2009.</p>
                    <?php
                    $fsfb = setting('social_facebook',  '#');
                    $fstw = setting('social_twitter',    '#');
                    $fsig = setting('social_instagram',  '#');
                    $fsli = setting('social_linkedin',   '#');
                    $fsyt = setting('social_youtube',    '#');
                    ?>
                    <div class="social-links">
                        <a href="<?= e($fsfb) ?>" class="social-link" aria-label="Facebook" <?= $fsfb !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-facebook"></i></a>
                        <a href="<?= e($fstw) ?>" class="social-link" aria-label="Twitter" <?= $fstw !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-twitter-x"></i></a>
                        <a href="<?= e($fsig) ?>" class="social-link" aria-label="Instagram" <?= $fsig !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-instagram"></i></a>
                        <a href="<?= e($fsli) ?>" class="social-link" aria-label="LinkedIn" <?= $fsli !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-linkedin"></i></a>
                        <a href="<?= e($fsyt) ?>" class="social-link" aria-label="YouTube" <?= $fsyt !== '#' ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>/"><i class="bi bi-chevron-right"></i> Home</a></li>
                        <li><a href="<?= BASE_URL ?>/about"><i class="bi bi-chevron-right"></i> About Us</a></li>
                        <li><a href="<?= BASE_URL ?>/services"><i class="bi bi-chevron-right"></i> Services</a></li>
                        <li><a href="<?= BASE_URL ?>/team"><i class="bi bi-chevron-right"></i> Our Team</a></li>
                        <li><a href="<?= BASE_URL ?>/portfolio"><i class="bi bi-chevron-right"></i> Portfolio</a></li>
                        <li><a href="<?= BASE_URL ?>/reports"><i class="bi bi-chevron-right"></i> Reports</a></li>
                        <li><a href="<?= BASE_URL ?>/blog"><i class="bi bi-chevron-right"></i> Blog</a></li>
                        <li><a href="<?= BASE_URL ?>/contact"><i class="bi bi-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Get In Touch</h4>
                    <?php
                    $fAddr  = setting('contact_address',  'P.O. Box 116842, Wakiso, Uganda');
                    $fPhone = setting('contact_phone_1',  '+256 772 935 325');
                    $fEmail = setting('contact_email',    'Wandacommunicationsug@gmail.com');
                    $fHours = setting('contact_hours',    'Mon – Fri: 8:00am – 6:00pm');
                    ?>
                    <div class="footer-contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span><?= nl2br(e($fAddr)) ?></span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <a href="tel:<?= e(preg_replace('/\s+/', '', $fPhone)) ?>"><?= e($fPhone) ?></a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <a href="mailto:<?= e($fEmail) ?>"><?= e($fEmail) ?></a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-clock-fill"></i>
                        <span><?= e($fHours) ?></span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= e(setting('site_title', 'Wanda Communications Uganda')) ?>. All rights reserved.</p>
                <a href="<?= BASE_URL ?>/admin/login" class="footer-admin-link">Admin</a>
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>/js/main.js"></script>
    <?= $extraScripts ?? '' ?>
</body>

</html>