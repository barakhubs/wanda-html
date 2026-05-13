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

<!-- TEAM SECTION -->
<section class="team-section" style="padding: var(--section-py) 0;">
    <div class="container">

        <div class="team-section-header reveal">
            <h2>The People Behind the Work</h2>
            <p>Experienced strategists, creatives, and advocates united by a shared belief in the power of purposeful communication.</p>
        </div>

        <?php if (empty($members)) : ?>
            <div class="text-center" style="padding:4rem 0;color:var(--text-muted)">
                <i class="bi bi-people" style="font-size:3rem;display:block;margin-bottom:1rem"></i>
                <p>Team members coming soon!</p>
            </div>
        <?php else : ?>
            <div class="team-grid">
                <?php foreach ($members as $member) :
                    $photoSrc = $member['photo']
                        ? BASE_URL . '/' . $member['photo']
                        : null;
                    $skills = $member['skills'] ?? [];
                ?>
                    <div class="team-card reveal">
                        <!-- Gradient banner -->
                        <div class="team-card-banner" style="background: <?= e($member['gradient_css']) ?>">
                            <!-- Circular avatar overlapping the banner -->
                            <div class="team-avatar">
                                <?php if ($photoSrc) : ?>
                                    <img src="<?= e($photoSrc) ?>" alt="<?= e($member['name']) ?>">
                                <?php else : ?>
                                    <i class="bi <?= e($member['fallback_icon'] ?? 'bi-person-fill') ?>"></i>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card body -->
                        <div class="team-card-body">
                            <h3 class="team-name"><?= e($member['name']) ?></h3>
                            <span class="team-role"><?= e($member['role']) ?></span>

                            <?php if ($member['bio_1'] || $member['bio_2']) : ?>
                                <div class="team-bio-wrap">
                                    <?php if ($member['bio_1']) : ?>
                                        <p class="team-bio"><?= e($member['bio_1']) ?></p>
                                    <?php endif; ?>
                                    <?php if ($member['bio_2']) : ?>
                                        <p class="team-bio"><?= e($member['bio_2']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <button class="team-bio-toggle" aria-expanded="false" hidden>
                                    Read more <i class="bi bi-chevron-down"></i>
                                </button>
                            <?php endif; ?>

                            <?php if (!empty($skills)) : ?>
                                <div class="team-skills">
                                    <?php foreach ($skills as $skill) : ?>
                                        <span class="skill-tag"><?= e($skill) ?></span>
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

<script>
    (function() {
        document.querySelectorAll('.team-bio-wrap').forEach(function(wrap) {
            var btn = wrap.nextElementSibling;
            if (!btn || !btn.classList.contains('team-bio-toggle')) return;

            // Only reveal the button when text is actually clamped
            if (wrap.scrollHeight > wrap.clientHeight + 1) {
                btn.removeAttribute('hidden');
            }

            btn.addEventListener('click', function() {
                var expanded = btn.getAttribute('aria-expanded') === 'true';
                if (expanded) {
                    wrap.classList.remove('is-expanded');
                    btn.setAttribute('aria-expanded', 'false');
                    btn.innerHTML = 'Read more <i class="bi bi-chevron-down"></i>';
                } else {
                    wrap.classList.add('is-expanded');
                    btn.setAttribute('aria-expanded', 'true');
                    btn.innerHTML = 'Read less <i class="bi bi-chevron-up"></i>';
                }
            });
        });
    }());
</script>
<section class="work-with-us">
    <div class="container">
        <div class="work-with-us-inner reveal">
            <div class="work-with-us-text">
                <h2>Join Our Team</h2>
                <p>We are always looking for talented, passionate individuals who believe in the power of strategic communication to drive lasting change.</p>
            </div>
            <div class="work-with-us-cta" style="flex-shrink:0">
                <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-lg">
                    Get in Touch <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA STRIP -->
<section class="cta-strip">
    <div class="cta-strip-pattern"></div>
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