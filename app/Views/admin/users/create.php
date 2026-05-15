<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-person-plus-fill"></i> Add Member</h1>
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
                <span><i class="bi bi-person-badge"></i> New Member Account</span>
            </div>

            <form method="post" action="<?= BASE_URL ?>/admin/users/create" class="admin-form">
                <?= csrfField() ?>

                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="username">Username <span class="required">*</span></label>
                        <input type="text" id="username" name="username"
                            value="<?= e($data['username'] ?? '') ?>"
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
                            value="<?= e($data['full_name'] ?? '') ?>"
                            placeholder="Jane Doe"
                            maxlength="120"
                            required>
                    </div>
                </div>

                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email"
                            value="<?= e($data['email'] ?? '') ?>"
                            placeholder="jane@example.com"
                            maxlength="180"
                            required>
                        <span class="form-hint">Login credentials will be sent to this address.</span>
                    </div>
                </div>

                <div class="admin-card" style="background:#f8fafc;margin-top:.5rem">
                    <p style="font-size:.88rem;color:var(--admin-muted);margin:0">
                        <i class="bi bi-info-circle"></i>
                        A secure password will be <strong>auto-generated</strong> and emailed to the member.
                        Members can manage <strong>Blog, Portfolio, Team, Testimonials, Gallery</strong> and <strong>Reports</strong>.
                        They <strong>cannot</strong> access Site Settings or User Management.
                    </p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-adm btn-adm-primary">
                        <i class="bi bi-check-lg"></i> Create Member
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