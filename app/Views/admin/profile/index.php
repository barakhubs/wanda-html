<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-person-circle"></i> My Profile</h1>
        <div class="admin-topbar-actions">
            <span style="font-size:.85rem;color:var(--admin-muted)">
                Logged in as <strong><?= e($_SESSION['admin_username'] ?? 'Admin') ?></strong>
            </span>
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
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul style="margin:.3rem 0 0 1.2rem;padding:0">
                    <?php foreach ($errors as $err) : ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Profile header card -->
        <div class="admin-card profile-header-card">
            <div class="profile-avatar-wrap">
                <?php if (!empty($admin['avatar'])) : ?>
                    <img src="<?= BASE_URL . '/' . e($admin['avatar']) ?>"
                        alt="Profile avatar"
                        class="profile-avatar-img">
                <?php else : ?>
                    <div class="profile-avatar-placeholder">
                        <i class="bi bi-person-fill"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="profile-header-info">
                <h2 class="profile-display-name">
                    <?= e($admin['full_name'] ?: $admin['username']) ?>
                </h2>
                <p class="profile-username-tag">@<?= e($admin['username']) ?></p>
                <?php if (!empty($admin['email'])) : ?>
                    <p class="profile-email"><i class="bi bi-envelope"></i> <?= e($admin['email']) ?></p>
                <?php endif; ?>
                <?php if (!empty($admin['bio'])) : ?>
                    <p class="profile-bio"><?= e($admin['bio']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab navigation -->
        <div class="settings-tabs" role="tablist">
            <button type="button" class="settings-tab active" data-target="tab-profile">
                <i class="bi bi-person"></i> Profile Info
            </button>
            <button type="button" class="settings-tab" data-target="tab-username">
                <i class="bi bi-at"></i> Username
            </button>
            <button type="button" class="settings-tab" data-target="tab-password">
                <i class="bi bi-shield-lock"></i> Password
            </button>
        </div>

        <!-- TAB 1 — PROFILE INFO -->
        <div class="settings-panel active" id="tab-profile">
            <div class="admin-card">
                <div class="admin-card-title">
                    <span><i class="bi bi-person-lines-fill"></i> Profile Information</span>
                </div>

                <form method="post" action="<?= BASE_URL ?>/admin/profile"
                    enctype="multipart/form-data" class="admin-form">
                    <?= csrfField() ?>

                    <div class="form-row col-2">
                        <div class="form-group">
                            <label for="full_name">Full Name <span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name"
                                value="<?= e($admin['full_name'] ?? '') ?>"
                                placeholder="Jane Doe"
                                maxlength="120"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email"
                                value="<?= e($admin['email'] ?? '') ?>"
                                placeholder="jane@example.com"
                                maxlength="180">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio / Short Description</label>
                        <textarea id="bio" name="bio" rows="4"
                            placeholder="A short description about yourself…"
                            maxlength="1000"><?= e($admin['bio'] ?? '') ?></textarea>
                        <span class="form-hint">Maximum 1 000 characters.</span>
                    </div>

                    <div class="form-group">
                        <label for="avatar">Profile Avatar</label>
                        <?php if (!empty($admin['avatar'])) : ?>
                            <div class="current-thumb" style="margin-bottom:.6rem">
                                <img src="<?= BASE_URL . '/' . e($admin['avatar']) ?>"
                                    alt="Current avatar"
                                    style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:2px solid var(--admin-border)">
                                <span class="form-hint" style="display:block;margin-top:.25rem">Upload a new image to replace the current avatar.</span>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="avatar" name="avatar"
                            accept="image/jpeg,image/png,image/webp,image/gif">
                        <span class="form-hint">JPEG, PNG, WebP or GIF — max 2 MB.</span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-adm btn-adm-primary">
                            <i class="bi bi-check-lg"></i> Save Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB 2 — USERNAME -->
        <div class="settings-panel" id="tab-username">
            <div class="admin-card">
                <div class="admin-card-title">
                    <span><i class="bi bi-at"></i> Change Username</span>
                </div>

                <form method="post" action="<?= BASE_URL ?>/admin/profile/username"
                    class="admin-form">
                    <?= csrfField() ?>

                    <div class="form-group" style="max-width:420px">
                        <label for="username">New Username <span class="required">*</span></label>
                        <input type="text" id="username" name="username"
                            value="<?= e($admin['username']) ?>"
                            placeholder="admin_username"
                            pattern="[a-zA-Z0-9_\-]{3,80}"
                            maxlength="80"
                            required
                            autocomplete="username">
                        <span class="form-hint">Letters, numbers, underscores and hyphens only (3–80 chars).</span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-adm btn-adm-primary">
                            <i class="bi bi-check-lg"></i> Update Username
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB 3 — PASSWORD -->
        <div class="settings-panel" id="tab-password">
            <div class="admin-card">
                <div class="admin-card-title">
                    <span><i class="bi bi-shield-lock-fill"></i> Change Password</span>
                </div>

                <form method="post" action="<?= BASE_URL ?>/admin/profile/password"
                    class="admin-form" autocomplete="off">
                    <?= csrfField() ?>

                    <div class="form-group" style="max-width:420px">
                        <label for="current_password">Current Password <span class="required">*</span></label>
                        <input type="password" id="current_password" name="current_password"
                            placeholder="Enter your current password"
                            autocomplete="current-password"
                            required>
                    </div>

                    <div class="form-group" style="max-width:420px">
                        <label for="new_password">New Password <span class="required">*</span></label>
                        <input type="password" id="new_password" name="new_password"
                            placeholder="Minimum 8 characters"
                            minlength="8"
                            autocomplete="new-password"
                            required>
                    </div>

                    <div class="form-group" style="max-width:420px">
                        <label for="confirm_password">Confirm New Password <span class="required">*</span></label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            placeholder="Repeat new password"
                            minlength="8"
                            autocomplete="new-password"
                            required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-adm btn-adm-danger">
                            <i class="bi bi-lock-fill"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div><!-- .admin-content -->
</div><!-- .admin-main -->

<style>
    /* ── Profile page styles ── */
    .profile-header-card {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .profile-avatar-wrap {
        flex-shrink: 0;
    }

    .profile-avatar-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--admin-border);
    }

    .profile-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--admin-muted);
        border: 3px solid var(--admin-border);
    }

    .profile-header-info {
        flex: 1;
        min-width: 0;
    }

    .profile-display-name {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: .2rem;
        color: var(--admin-text);
    }

    .profile-username-tag {
        font-size: .9rem;
        color: var(--admin-muted);
        margin-bottom: .35rem;
    }

    .profile-email {
        font-size: .88rem;
        color: var(--admin-muted);
        margin-bottom: .35rem;
    }

    .profile-bio {
        font-size: .9rem;
        color: var(--admin-text);
        line-height: 1.55;
        margin-top: .5rem;
        max-width: 600px;
    }

    .required {
        color: var(--admin-danger);
    }

    .form-actions {
        margin-top: 1rem;
    }
</style>

<script>
    /* Profile page — tab switching (reuses existing settings-tab/panel logic) */
    (function() {
        'use strict';
        var tabs = document.querySelectorAll('.settings-tab');
        var panels = document.querySelectorAll('.settings-panel');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                tabs.forEach(function(t) {
                    t.classList.remove('active');
                });
                panels.forEach(function(p) {
                    p.classList.remove('active');
                });

                tab.classList.add('active');
                var target = document.getElementById(tab.dataset.target);
                if (target) {
                    target.classList.add('active');
                }
            });
        });
    }());
</script>