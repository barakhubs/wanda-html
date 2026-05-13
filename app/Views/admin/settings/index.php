<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-gear-fill"></i> Site Settings</h1>
        <div class="admin-topbar-actions">
            <button form="settingsForm" type="submit" class="btn-adm btn-adm-primary">
                <i class="bi bi-check-lg"></i> Save All Settings
            </button>
        </div>
    </div>

    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>">
                <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.2rem">
                    <?php foreach ($errors as $err) : ?><li><?= e($err) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Tab navigation -->
        <div class="settings-tabs" role="tablist">
            <button type="button" class="settings-tab active" data-target="tab-general">
                <i class="bi bi-globe2"></i> General
            </button>
            <button type="button" class="settings-tab" data-target="tab-contact">
                <i class="bi bi-telephone"></i> Contact Details
            </button>
            <button type="button" class="settings-tab" data-target="tab-branding">
                <i class="bi bi-image"></i> Branding &amp; Logos
            </button>
            <button type="button" class="settings-tab" data-target="tab-social">
                <i class="bi bi-share"></i> Social Media
            </button>
            <button type="button" class="settings-tab" data-target="tab-email">
                <i class="bi bi-envelope-at"></i> Email &amp; SMTP
            </button>
        </div>

        <form id="settingsForm" method="post" enctype="multipart/form-data" class="admin-form">
            <?= csrfField() ?>

            <!-- TAB 1 — GENERAL -->
            <div class="settings-panel active" id="tab-general">
                <div class="admin-card">
                    <div class="admin-card-title"><i class="bi bi-globe2"></i> General</div>
                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="site_title">Website Title</label>
                            <input type="text" id="site_title" name="site_title"
                                value="<?= e($s['site_title'] ?? 'Wanda Communications Uganda') ?>"
                                placeholder="Wanda Communications Uganda">
                            <span class="form-hint">Used in <code>&lt;title&gt;</code> tags and meta og:title.</span>
                        </div>
                        <div class="form-group">
                            <label for="site_tagline">Tagline / Slogan</label>
                            <input type="text" id="site_tagline" name="site_tagline"
                                value="<?= e($s['site_tagline'] ?? 'Strategic Communications for Impact') ?>"
                                placeholder="Strategic Communications for Impact">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2 — CONTACT DETAILS -->
            <div class="settings-panel" id="tab-contact">
                <div class="admin-card">
                    <div class="admin-card-title"><i class="bi bi-telephone"></i> Contact Details</div>
                    <div class="form-group">
                        <label for="contact_address">Office Address</label>
                        <textarea id="contact_address" name="contact_address" rows="2"
                            placeholder="P.O. Box 116842, Wakiso, Uganda"><?= e($s['contact_address'] ?? '') ?></textarea>
                    </div>
                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="contact_email">Contact / Enquiries Email</label>
                            <input type="email" id="contact_email" name="contact_email"
                                value="<?= e($s['contact_email'] ?? '') ?>"
                                placeholder="Wandacommunicationsug@gmail.com">
                        </div>
                        <div class="form-group">
                            <label for="contact_hours">Working Hours</label>
                            <input type="text" id="contact_hours" name="contact_hours"
                                value="<?= e($s['contact_hours'] ?? '') ?>"
                                placeholder="Mon–Fri 8:00 AM – 6:00 PM">
                        </div>
                    </div>
                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="contact_phone_1">Primary Phone</label>
                            <input type="text" id="contact_phone_1" name="contact_phone_1"
                                value="<?= e($s['contact_phone_1'] ?? '') ?>"
                                placeholder="+256 772 935 325">
                        </div>
                        <div class="form-group">
                            <label for="contact_phone_2">Secondary Phone</label>
                            <input type="text" id="contact_phone_2" name="contact_phone_2"
                                value="<?= e($s['contact_phone_2'] ?? '') ?>"
                                placeholder="+256 700 112 974">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3 — BRANDING & LOGOS -->
            <div class="settings-panel" id="tab-branding">
                <div class="admin-card">
                    <div class="admin-card-title"><i class="bi bi-image"></i> Logos &amp; Branding</div>
                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="logo">Header Logo</label>
                            <?php if (!empty($s['logo_path'])) : ?>
                                <img src="<?= e(BASE_URL . '/' . $s['logo_path']) ?>"
                                    alt="Current header logo" class="logo-preview">
                            <?php endif; ?>
                            <input type="file" id="logo" name="logo"
                                accept="image/jpeg,image/png,image/webp,image/gif">
                            <span class="form-hint">PNG or WebP with transparency recommended. Max 5 MB.</span>
                        </div>
                        <div class="form-group">
                            <label for="footer_logo">Footer Logo</label>
                            <?php if (!empty($s['footer_logo_path'])) : ?>
                                <img src="<?= e(BASE_URL . '/' . $s['footer_logo_path']) ?>"
                                    alt="Current footer logo" class="logo-preview logo-preview-dark">
                            <?php endif; ?>
                            <input type="file" id="footer_logo" name="footer_logo"
                                accept="image/jpeg,image/png,image/webp,image/gif">
                            <span class="form-hint">Light / white version for the dark footer background. Max 5 MB.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4 — SOCIAL MEDIA -->
            <div class="settings-panel" id="tab-social">
                <div class="admin-card">
                    <div class="admin-card-title"><i class="bi bi-share"></i> Social Media Links</div>
                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="social_facebook"><i class="bi bi-facebook" style="color:#1877f2"></i> Facebook URL</label>
                            <input type="url" id="social_facebook" name="social_facebook"
                                value="<?= e($s['social_facebook'] ?? '') ?>"
                                placeholder="https://www.facebook.com/wandacommunicationsug">
                        </div>
                        <div class="form-group">
                            <label for="social_twitter"><i class="bi bi-twitter-x"></i> Twitter / X URL</label>
                            <input type="url" id="social_twitter" name="social_twitter"
                                value="<?= e($s['social_twitter'] ?? '') ?>"
                                placeholder="https://x.com/wandacommunications">
                        </div>
                        <div class="form-group">
                            <label for="social_instagram"><i class="bi bi-instagram" style="color:#e1306c"></i> Instagram URL</label>
                            <input type="url" id="social_instagram" name="social_instagram"
                                value="<?= e($s['social_instagram'] ?? '') ?>"
                                placeholder="https://www.instagram.com/wandacommunicationsug">
                        </div>
                        <div class="form-group">
                            <label for="social_linkedin"><i class="bi bi-linkedin" style="color:#0a66c2"></i> LinkedIn URL</label>
                            <input type="url" id="social_linkedin" name="social_linkedin"
                                value="<?= e($s['social_linkedin'] ?? '') ?>"
                                placeholder="https://www.linkedin.com/company/wanda-communications">
                        </div>
                        <div class="form-group">
                            <label for="social_youtube"><i class="bi bi-youtube" style="color:#ff0000"></i> YouTube URL</label>
                            <input type="url" id="social_youtube" name="social_youtube"
                                value="<?= e($s['social_youtube'] ?? '') ?>"
                                placeholder="https://www.youtube.com/@wandacommunications">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5 — EMAIL & SMTP -->
            <div class="settings-panel" id="tab-email">
                <div class="admin-card">
                    <div class="admin-card-title"><i class="bi bi-envelope"></i> Contact Form</div>
                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="form_recipient_email">Form Recipient Email</label>
                            <input type="email" id="form_recipient_email" name="form_recipient_email"
                                value="<?= e($s['form_recipient_email'] ?? '') ?>"
                                placeholder="Wandacommunicationsug@gmail.com">
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-title"><i class="bi bi-send"></i> SMTP Configuration</div>
                    <div class="form-row col-3">
                        <div class="form-group">
                            <label for="smtp_host">SMTP Host</label>
                            <input type="text" id="smtp_host" name="smtp_host"
                                value="<?= e($s['smtp_host'] ?? '') ?>"
                                placeholder="smtp.gmail.com" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="smtp_port">Port</label>
                            <input type="number" id="smtp_port" name="smtp_port"
                                value="<?= e($s['smtp_port'] ?? '587') ?>"
                                placeholder="587" min="1" max="65535">
                        </div>
                        <div class="form-group">
                            <label for="smtp_encryption">Encryption</label>
                            <select id="smtp_encryption" name="smtp_encryption">
                                <option value="tls"  <?= ($s['smtp_encryption'] ?? 'tls') === 'tls'  ? 'selected' : '' ?>>TLS (STARTTLS — port 587)</option>
                                <option value="ssl"  <?= ($s['smtp_encryption'] ?? '') === 'ssl'      ? 'selected' : '' ?>>SSL (port 465)</option>
                                <option value="none" <?= ($s['smtp_encryption'] ?? '') === 'none'     ? 'selected' : '' ?>>None</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="smtp_from_name">From Name</label>
                            <input type="text" id="smtp_from_name" name="smtp_from_name"
                                value="<?= e($s['smtp_from_name'] ?? 'Wanda Communications Uganda') ?>">
                        </div>
                        <div class="form-group">
                            <label for="smtp_from_email">From Email</label>
                            <input type="email" id="smtp_from_email" name="smtp_from_email"
                                value="<?= e($s['smtp_from_email'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="smtp_username">SMTP Username</label>
                            <input type="text" id="smtp_username" name="smtp_username"
                                value="<?= e($s['smtp_username'] ?? '') ?>" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="smtp_password">SMTP Password</label>
                            <input type="password" id="smtp_password" name="smtp_password"
                                placeholder="<?= !empty($s['smtp_password']) ? '(saved — leave blank to keep)' : 'Enter SMTP password' ?>"
                                autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;padding-bottom:.5rem">
                <button type="submit" class="btn-adm btn-adm-primary btn-adm-lg">
                    <i class="bi bi-check-lg"></i> Save All Settings
                </button>
            </div>

        </form><!-- #settingsForm -->
    </div><!-- .admin-content -->
</div><!-- .admin-main -->
