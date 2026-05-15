<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-person-gear"></i> Edit Member</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/users" class="btn-adm btn-adm-outline">
                <i class="bi bi-arrow-left"></i>
                <span class="btn-label">Back to Users</span>
            </a>
        </div>
    </div>

    <div class="admin-content">

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

        <div class="admin-card">
            <div class="admin-card-title">
                <span><i class="bi bi-person-lines-fill"></i> Editing: <em><?= e($user['username']) ?></em></span>
                <span class="badge badge-muted">Member</span>
            </div>

            <form method="post"
                action="<?= BASE_URL ?>/admin/users/edit/<?= (int) $user['id'] ?>"
                class="admin-form">
                <?= csrfField() ?>

                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="username">Username <span class="required">*</span></label>
                        <input type="text" id="username" name="username"
                            value="<?= e($user['username']) ?>"
                            placeholder="e.g. jane_doe"
                            pattern="[a-zA-Z0-9_\-]{3,80}"
                            maxlength="80"
                            required
                            autocomplete="username">
                        <span class="form-hint">Letters, numbers, underscores and hyphens (3–80 chars).</span>
                    </div>
                    <div class="form-group">
                        <label for="full_name">Full Name <span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name"
                            value="<?= e($user['full_name'] ?? '') ?>"
                            placeholder="Jane Doe"
                            maxlength="120"
                            required>
                    </div>
                </div>

                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                            value="<?= e($user['email'] ?? '') ?>"
                            placeholder="jane@example.com"
                            maxlength="180">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password"
                            placeholder="Leave blank to keep current password"
                            minlength="8"
                            autocomplete="new-password">
                        <span class="form-hint">Only fill in if you want to reset this member's password (min 8 chars).</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-adm btn-adm-primary">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                    <a href="<?= BASE_URL ?>/admin/users" class="btn-adm btn-adm-outline">Cancel</a>
                </div>
            </form>
        </div>

    </div><!-- .admin-content -->
</div><!-- .admin-main -->

<style>
    .required {
        color: var(--admin-danger);
    }

    .form-actions {
        display: flex;
        gap: .6rem;
        align-items: center;
        margin-top: 1.25rem;
        flex-wrap: wrap;
    }
</style>