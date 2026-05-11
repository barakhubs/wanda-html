<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle   = 'About Us | Wanda Communications Uganda';
$pageDesc    = 'About Wanda Communications Uganda — a dynamic, full-service communications agency dedicated to transforming how organizations share their stories.';
$currentPage = 'about';
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
            <span>About Us</span>
        </div>
        <h1>About Wanda Communications</h1>
        <p>A dynamic, full-service communications agency dedicated to transforming how organizations share their stories and connect with their audiences.</p>
    </div>
</section>

<!-- ABOUT INTRO -->
<section class="about-intro">
    <div class="container">
        <div class="about-grid">
            <div class="about-visual reveal-left">
                <div class="about-visual-inner">
                    <img
                        src="<?= BASE_URL ?>/images/staff.JPG"
                        alt="Wanda Communications Team"
                        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:50%">
                    <div class="about-stats-bubble about-bubble-1">
                        <span class="ab-num">15+</span>
                        <span class="ab-text">Years</span>
                    </div>
                    <div class="about-stats-bubble about-bubble-2">
                        <span class="ab-num">50+</span>
                        <span class="ab-text">Clients</span>
                    </div>
                    <div class="about-stats-bubble about-bubble-3">
                        <span class="ab-num">100+</span>
                        <span class="ab-text">Projects</span>
                    </div>
                </div>
            </div>

            <div class="about-text reveal-right">
                <span class="section-tag">Who We Are</span>
                <h2>Empowering Organizations Through Strategic Communication</h2>
                <p>Wanda Communications Uganda is a dynamic, full-service communications agency dedicated to transforming how organizations share their stories and connect with their audiences.</p>
                <p>Founded by a team of seasoned experts, we empower clients with strategic, creative, and results-driven communication solutions that inspire action and foster lasting impact.</p>
                <p>From strategic communication plans to high-quality IEC materials, we ensure top-tier quality while meeting even the tightest deadlines — because impactful communication cannot wait.</p>

                <div class="about-features">
                    <div class="about-feature"><i class="bi bi-check-circle-fill"></i> Strategic Communication Plans</div>
                    <div class="about-feature"><i class="bi bi-check-circle-fill"></i> Advocacy Campaign Planning</div>
                    <div class="about-feature"><i class="bi bi-check-circle-fill"></i> Content Creation &amp; Writing</div>
                    <div class="about-feature"><i class="bi bi-check-circle-fill"></i> Photography &amp; Videography</div>
                    <div class="about-feature"><i class="bi bi-check-circle-fill"></i> Website Content Development</div>
                    <div class="about-feature"><i class="bi bi-check-circle-fill"></i> Media Engagement</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MISSION, GOAL & VALUES -->
<section class="mission-values">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Our Foundation</span>
            <h2>Mission, Goal &amp; Approach</h2>
            <p>Everything we do is guided by a commitment to excellence, innovation, and impact.</p>
        </div>

        <div class="mv-grid">
            <div class="mv-card reveal delay-1">
                <div class="mv-icon"><i class="bi bi-rocket-takeoff"></i></div>
                <h3>Our Mission</h3>
                <p>Delivering strategic, innovative, creative, and results-driven communication solutions that inspire action and foster lasting impact.</p>
            </div>
            <div class="mv-card reveal delay-2">
                <div class="mv-icon"><i class="bi bi-trophy"></i></div>
                <h3>Our Goal</h3>
                <p>Strategic, creative, and results-driven communication solutions that inspire action and foster lasting impact for every client we serve.</p>
            </div>
            <div class="mv-card reveal delay-3">
                <div class="mv-icon"><i class="bi bi-lightbulb"></i></div>
                <h3>Our Approach</h3>
                <p>Data-driven planning, audience-focused messaging, strategic campaign implementation, and continuous performance evaluation and optimisation.</p>
            </div>
        </div>
    </div>
</section>

<!-- CORE OBJECTIVES -->
<section style="background: var(--bg); padding: var(--section-py) 0">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">What Drives Us</span>
            <h2>Our Core Objectives</h2>
            <p>Four pillars that guide every project we undertake.</p>
        </div>

        <div class="services-grid-full" style="grid-template-columns: repeat(2, 1fr)">
            <div class="service-card-full reveal delay-1">
                <div class="service-icon"><i class="bi bi-file-earmark-richtext"></i></div>
                <h3>High-Quality Communication Products</h3>
                <p>Design and deliver communication products that effectively convey clients' narratives and strategic messages.</p>
                <ul>
                    <li>Written materials &amp; digital media</li>
                    <li>Photography &amp; videography</li>
                    <li>Brand storytelling</li>
                    <li>Audience engagement content</li>
                </ul>
            </div>
            <div class="service-card-full reveal delay-2">
                <div class="service-icon"><i class="bi bi-journals"></i></div>
                <h3>Comprehensive Programme Documentation</h3>
                <p>Capture and archive programme outcomes through high-quality visual and written documentation.</p>
                <ul>
                    <li>High-quality photo &amp; video capture</li>
                    <li>Digital archives &amp; galleries</li>
                    <li>Reports, case studies &amp; briefs</li>
                    <li>Visually appealing publications</li>
                </ul>
            </div>
            <div class="service-card-full reveal delay-3">
                <div class="service-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <h3>Communication Strategies</h3>
                <p>Develop and implement evidence-based communication strategies tailored to each client's unique context.</p>
                <ul>
                    <li>Data-driven communication planning</li>
                    <li>Audience-focused messaging</li>
                    <li>Strategic campaign implementation</li>
                    <li>Performance evaluation &amp; optimisation</li>
                </ul>
            </div>
            <div class="service-card-full reveal delay-4">
                <div class="service-icon"><i class="bi bi-megaphone"></i></div>
                <h3>Enhance Organisational Visibility</h3>
                <p>Build and strengthen client visibility through targeted campaigns and premium branded materials.</p>
                <ul>
                    <li>Branded IEC materials</li>
                    <li>Multimedia content production</li>
                    <li>Testimonials &amp; case studies</li>
                    <li>Social media growth campaigns</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CLIENTS STRIP -->
<div class="clients-strip">
    <div class="container">
        <p class="clients-label">Organisations &amp; donors we have worked with</p>
        <div class="clients-logos">
            <span class="client-logo">Plan International</span>
            <span class="client-logo">USAID</span>
            <span class="client-logo">UK Aid</span>
            <span class="client-logo">SIDA</span>
            <span class="client-logo">DANIDA</span>
            <span class="client-logo">European Union</span>
            <span class="client-logo">LEGO Foundation</span>
            <span class="client-logo">Irish Aid</span>
        </div>
    </div>
</div>

<!-- CTA STRIP -->
<section class="cta-strip">
    <div class="container">
        <h2 class="reveal">Ready to Transform Your Communications?</h2>
        <p class="reveal delay-1">Partner with us for strategic, creative communication solutions that deliver real results.</p>
        <div class="reveal delay-2">
            <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-lg">
                Start a Conversation <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>