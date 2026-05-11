<?php
require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle   = 'Our Team | Wanda Communications Uganda';
$pageDesc    = 'Meet the experienced team of communication professionals behind Wanda Communications Uganda.';
$currentPage = 'team';

$teamModel = new TeamMember();
$members   = $teamModel->getAll();

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
            <span>Our Team</span>
        </div>
        <h1>Meet Our Team</h1>
        <p>A passionate collective of communication professionals dedicated to amplifying impact across Uganda and East Africa.</p>
    </div>
</section>

<!-- ══════════════════════════════════════════
       TEAM SECTION
  ══════════════════════════════════════════ -->
<section class="team-full">
    <div class="container">

        <?php if (empty($members)) : ?>
            <div class="text-center" style="padding:4rem 0;color:var(--text-muted)">
                <i class="bi bi-people" style="font-size:3rem;display:block;margin-bottom:1rem"></i>
                <p>Team members coming soon!</p>
            </div>
        <?php else : ?>
            <div class="team-grid-full">
                <?php foreach ($members as $member) :
                    $photoSrc = $member['photo']
                        ? BASE_URL . '/' . $member['photo']
                        : null;
                    $skills = $member['skills'] ?? [];
                ?>
                    <div class="team-card-full reveal">
                        <div class="team-card-header" style="background: <?= e($member['gradient_css']) ?>">
                            <?php if ($photoSrc) : ?>
                                <img src="<?= e($photoSrc) ?>" alt="<?= e($member['name']) ?>" class="team-photo">
                            <?php else : ?>
                                <div class="team-photo-fallback">
                                    <i class="bi <?= e($member['fallback_icon'] ?? 'bi-person-fill') ?>"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="team-card-body">
                            <h3><?= e($member['name']) ?></h3>
                            <span class="team-role"><?= e($member['role']) ?></span>
                            <?php if ($member['bio_1']) : ?>
                                <p class="team-bio"><?= e($member['bio_1']) ?></p>
                            <?php endif; ?>
                            <?php if ($member['bio_2']) : ?>
                                <p class="team-bio"><?= e($member['bio_2']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($skills)) : ?>
                                <div class="team-skills">
                                    <?php foreach ($skills as $skill) : ?>
                                        <span class="skill-tag"><?= e($skill['skill_name']) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- WORK WITH US -->
<section class="work-with-us">
    <div class="container">
        <div class="work-with-us-inner reveal">
            <div class="work-with-us-text">
                <h2>Join Our Team</h2>
                <p>We are always looking for talented, passionate individuals who believe in the power of strategic communication to drive change.</p>
            </div>
            <div class="work-with-us-cta">
                <a href="<?= BASE_URL ?>/contact" class="btn btn-primary">
                    Get in Touch <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA STRIP -->
<section class="cta-strip">
    <div class="container">
        <h2 class="reveal">Ready to Work With Us?</h2>
        <p class="reveal delay-1">Our team brings strategic vision, creative expertise, and on-the-ground experience to every project.</p>
        <div class="reveal delay-2">
            <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-lg">
                Start a Conversation <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>