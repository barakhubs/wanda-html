<!-- PAGE HERO -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-geo1"></div>
    <div class="page-hero-geo2"></div>
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a>
            <i class="bi bi-chevron-right"></i>
            <span>Contact Us</span>
        </div>
        <h1>Get In Touch</h1>
        <p>We'd love to hear about your communication needs and explore how we can work together.</p>
    </div>
</section>

<!-- CONTACT SECTION -->
<section class="contact-section">
    <div class="container">
        <div class="contact-grid">

            <!-- Contact Details -->
            <div class="contact-info reveal-left">
                <h3>Contact Information</h3>
                <p class="contact-info-sub">Reach out — we respond within one business day.</p>
                <?php
                $cAddr   = setting('contact_address',  'P.O. Box 116842, Wakiso, Uganda, East Africa');
                $cEmail  = setting('contact_email',    'Wandacommunicationsug@gmail.com');
                $cPhone1 = setting('contact_phone_1',  '+256 772 935 325');
                $cPhone2 = setting('contact_phone_2',  '+256 700 112 974');
                $cHours  = setting('contact_hours',    'Monday – Friday: 8:00 AM – 6:00 PM');
                $csFb    = setting('social_facebook',  'https://www.facebook.com/wandacommunicationsug');
                $csTw    = setting('social_twitter',   'https://x.com/wandacommunications');
                $csIg    = setting('social_instagram', 'https://www.instagram.com/wandacommunicationsug');
                $csLi    = setting('social_linkedin',  'https://www.linkedin.com/company/wanda-communications');
                $csYt    = setting('social_youtube',   'https://www.youtube.com/@wandacommunications');
                ?>

                <div class="contact-detail">
                    <div class="contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <strong>Office Address</strong>
                        <p><?= nl2br(e($cAddr)) ?></p>
                    </div>
                </div>

                <div class="contact-detail">
                    <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <strong>Email Address</strong>
                        <p><a href="mailto:<?= e($cEmail) ?>"><?= e($cEmail) ?></a></p>
                    </div>
                </div>

                <div class="contact-detail">
                    <div class="contact-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div>
                        <strong>Phone Numbers</strong>
                        <p>
                            <a href="tel:<?= e(preg_replace('/\s+/', '', $cPhone1)) ?>"><?= e($cPhone1) ?></a><br>
                            <?php if ($cPhone2) : ?>
                                <a href="tel:<?= e(preg_replace('/\s+/', '', $cPhone2)) ?>"><?= e($cPhone2) ?></a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="contact-detail">
                    <div class="contact-icon"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <strong>Working Hours</strong>
                        <p><?= nl2br(e($cHours)) ?></p>
                    </div>
                </div>

                <div class="contact-social-wrap">
                    <div class="contact-social-label">Follow Us</div>
                    <div class="contact-social">
                        <a href="<?= e($csFb) ?>" class="soc-fb" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="<?= e($csTw) ?>" class="soc-tw" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X"><i class="bi bi-twitter-x"></i></a>
                        <a href="<?= e($csIg) ?>" class="soc-ig" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="<?= e($csLi) ?>" class="soc-li" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="<?= e($csYt) ?>" class="soc-yt" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-wrap reveal-right">
                <h3>Send Us a Message</h3>
                <div id="formSuccess" style="display:none; background:#d4edda; color:#155724; padding:1rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; border:1px solid #c3e6cb;">
                    <i class="bi bi-check-circle-fill"></i> Thank you! Your message has been sent successfully. We'll be in touch shortly.
                </div>

                <form
                    action="https://formsubmit.co/<?= e(setting('form_recipient_email', 'Wandacommunicationsug@gmail.com')) ?>"
                    method="POST"
                    id="contactForm">
                    <input type="text" name="_honey" style="display:none">
                    <input type="hidden" name="_captcha" value="false">
                    <input type="hidden" name="_next" value="<?= BASE_URL ?>/contact">
                    <input type="hidden" name="_subject" value="New Inquiry from Wanda Communications Website">

                    <div class="form-group">
                        <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" placeholder="Your full name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="your@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject <span class="required">*</span></label>
                        <input type="text" id="subject" name="subject" placeholder="What is this regarding?" required>
                    </div>

                    <div class="form-group">
                        <label for="service">Service of Interest</label>
                        <select id="service" name="service">
                            <option value="" disabled selected>Select a service</option>
                            <option value="Content Creation">Content Creation &amp; Writing</option>
                            <option value="Social Media">Social Media Management</option>
                            <option value="Website Content">Website Content Development</option>
                            <option value="Report Writing">Report Writing &amp; Documentation</option>
                            <option value="Capacity Building">Capacity Building &amp; Training</option>
                            <option value="Media Relations">Media Relations</option>
                            <option value="Branded Materials">Branded Materials &amp; Design</option>
                            <option value="Strategic Plans">Strategic Communication Plans</option>
                            <option value="Office Branding">Office Branding &amp; Signage</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="6" placeholder="Tell us about your project or inquiry..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Send Message <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- MAP SECTION -->
<section class="map-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Find Us</span>
            <h2>Our Location</h2>
            <p>We are based in Wakiso, Uganda — easily accessible from Kampala.</p>
        </div>
    </div>
    <div class="map-embed">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127672.49559316783!2d32.421913796679685!3d0.39855879999999834!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbf3a86f6d4a5%3A0xf9e82b940de82e3e!2sWakiso%2C%20Uganda!5e0!3m2!1sen!2sus!4v1715000000000!5m2!1sen!2sus"
            width="100%"
            height="400"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Wanda Communications Uganda — Wakiso Location"></iframe>
    </div>
</section>
<?= $extraScripts ?? '' ?>
