<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-plus-lg"></i> Add Team Member</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/team" class="btn-adm btn-adm-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="admin-content">

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.2rem">
                    <?php foreach ($errors as $err) : ?><li><?= e($err) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="admin-form">
            <?= csrfField() ?>

            <div class="admin-card">
                <div class="admin-card-title">Basic Info</div>
                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" value="<?= e($data['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role / Title *</label>
                        <input type="text" id="role" name="role" value="<?= e($data['role']) ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bio_1">Bio Paragraph 1</label>
                    <textarea id="bio_1" name="bio_1" rows="3"><?= e($data['bio_1']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="bio_2">Bio Paragraph 2</label>
                    <textarea id="bio_2" name="bio_2" rows="3"><?= e($data['bio_2']) ?></textarea>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Skills</div>
                <div id="skills-wrap">
                    <?php foreach ($skills as $skillVal) : ?>
                        <div class="skill-row">
                            <input type="text" name="skills[]" value="<?= e($skillVal) ?>" placeholder="Skill name">
                            <button type="button" class="btn-remove-skill" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn-adm btn-adm-outline btn-adm-sm" style="margin-top:.5rem" onclick="addSkill()">
                    <i class="bi bi-plus"></i> Add Skill
                </button>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Photo</div>
                <div class="form-group">
                    <input type="file" id="photo" name="photo" accept="image/*">
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Appearance</div>
                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="gradient_css">Card Gradient</label>
                        <select id="gradient_css" name="gradient_css">
                            <?php foreach ($gradients as $grad => $label) : ?>
                                <option value="<?= e($grad) ?>" <?= $data['gradient_css'] === $grad ? 'selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fallback_icon">Fallback Icon Class</label>
                        <input type="text" id="fallback_icon" name="fallback_icon" value="<?= e($data['fallback_icon']) ?>" placeholder="bi-person-fill">
                        <span class="form-hint">Used when no photo is set.</span>
                    </div>
                </div>
                <div class="form-group" style="max-width:200px">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="<?= (int)$data['sort_order'] ?>" min="0">
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Visibility</div>
                <div class="form-check">
                    <input type="checkbox" id="published" name="published" value="1" <?= $data['published'] ? 'checked' : '' ?>>
                    <label for="published">Visible on website</label>
                </div>
            </div>

            <button type="submit" class="btn-adm btn-adm-primary btn-adm-lg">
                <i class="bi bi-save"></i> Save Member
            </button>
        </form>

    </div>
</div>
