  <!-- ══════════════════════════════════════════
       FOOTER
  ══════════════════════════════════════════ -->
  <footer class="footer">
      <div class="container">
          <div class="footer-grid">

              <!-- Brand -->
              <div class="footer-brand">
                  <?php
                    $footerLogoSrc = !empty(setting('footer_logo_path'))
                        ? BASE_URL . '/' . setting('footer_logo_path')
                        : BASE_URL . '/logo.jpg';
                    $footerLogoAlt = setting('site_title', 'Wanda Communications Uganda');
                    ?>
                  <div class="footer-logo-wrap">
                      <img
                          src="<?= e($footerLogoSrc) ?>"
                          alt="<?= e($footerLogoAlt) ?>"
                          class="footer-logo"
                          onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                      <div class="footer-logo-text" style="display:none">
                          <div class="fw">WANDA</div>
                          <div class="fc">COMMUNICATIONS UGANDA</div>
                      </div>
                  </div>
                  <p>Your strategic partner in achieving communication excellence.
                      Delivering impactful development communication solutions across
                      Uganda and East Africa since 2009.</p>
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

              <!-- Quick links -->
              <div class="footer-col">
                  <h4>Quick Links</h4>
                  <ul>
                      <li><a href="<?= BASE_URL ?>/"><i class="bi bi-chevron-right"></i> Home</a></li>
                      <li><a href="<?= BASE_URL ?>/about"><i class="bi bi-chevron-right"></i> About Us</a></li>
                      <li><a href="<?= BASE_URL ?>/services"><i class="bi bi-chevron-right"></i> Services</a></li>
                      <li><a href="<?= BASE_URL ?>/team"><i class="bi bi-chevron-right"></i> Our Team</a></li>
                      <li><a href="<?= BASE_URL ?>/portfolio"><i class="bi bi-chevron-right"></i> Portfolio</a></li>
                      <li><a href="<?= BASE_URL ?>/blog"><i class="bi bi-chevron-right"></i> Blog</a></li>
                      <li><a href="<?= BASE_URL ?>/contact"><i class="bi bi-chevron-right"></i> Contact</a></li>
                  </ul>
              </div>

              <!-- Contact -->
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
              <p>Designed with <i class="bi bi-heart-fill" style="color:var(--primary)"></i> for Impact</p>
          </div>
      </div>
  </footer>

  <script src="<?= BASE_URL ?>/js/main.js"></script>
  <?= $extraScripts ?? '' ?>
  </body>

  </html>