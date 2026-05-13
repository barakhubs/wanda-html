<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-plus-lg"></i> New Portfolio Item</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/portfolio" class="btn-adm btn-adm-outline"><i class="bi bi-arrow-left"></i> Back</a>
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
                <div class="admin-card-title">Details</div>
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?= e($data['title']) ?>" required>
                </div>
                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category">
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= e($cat) ?>" <?= $data['category'] === $cat ? 'selected' : '' ?>><?= e(ucfirst($cat)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" value="<?= (int)$data['sort_order'] ?>" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label for="short_desc">Short Description</label>
                    <input type="text" id="short_desc" name="short_desc" value="<?= e($data['short_desc']) ?>">
                    <span class="form-hint">Shown in the portfolio card.</span>
                </div>
                <div class="form-group">
                    <label for="full_desc">Full Description</label>
                    <textarea id="full_desc" name="full_desc" rows="4"><?= e($data['full_desc']) ?></textarea>
                    <span class="form-hint">Shown in the lightbox popup.</span>
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
                        <label for="icon_class">Bootstrap Icon Class</label>
                        <input type="text" id="icon_class" name="icon_class" value="<?= e($data['icon_class']) ?>" placeholder="bi-camera">
                        <span class="form-hint">e.g. bi-camera, bi-film, bi-megaphone</span>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Thumbnail</div>
                <div class="form-group">
                    <label for="thumbnail">Upload Image (JPEG/PNG/WebP, max 5 MB)</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Settings</div>
                <div class="form-check" style="margin-bottom:.75rem">
                    <input type="checkbox" id="featured" name="featured" value="1" <?= $data['featured'] ? 'checked' : '' ?>>
                    <label for="featured">Featured on homepage</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="published" name="published" value="1" <?= $data['published'] ? 'checked' : '' ?>>
                    <label for="published">Published (visible on website)</label>
                </div>
            </div>

            <button type="submit" class="btn-adm btn-adm-primary btn-adm-lg">
                <i class="bi bi-save"></i> Save Item
            </button>
        </form>

    </div>
</div>
