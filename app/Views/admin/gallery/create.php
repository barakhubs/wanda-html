<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-cloud-upload"></i> Upload Gallery Image</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/gallery" class="btn-adm btn-adm-outline"><i class="bi bi-arrow-left"></i> Back</a>
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
                <div class="admin-card-title">Image</div>

                <div class="form-group">
                    <label for="image">Image File * (JPEG/PNG/WebP, max 5 MB)</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="alt_text">Alt Text</label>
                    <input type="text" id="alt_text" name="alt_text" value="<?= e($data['alt_text']) ?>" placeholder="Short description of the image">
                    <span class="form-hint">For accessibility and SEO.</span>
                </div>

                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" value="<?= (int)$data['sort_order'] ?>" min="0">
                    </div>
                    <div class="form-group" style="align-self:flex-end;padding-bottom:.3rem">
                        <div class="form-check" style="margin-bottom:.5rem">
                            <input type="checkbox" id="is_wide" name="is_wide" value="1" <?= $data['is_wide'] ? 'checked' : '' ?>>
                            <label for="is_wide">Wide tile (spans 2 columns)</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="published" name="published" value="1" <?= $data['published'] ? 'checked' : '' ?>>
                            <label for="published">Visible on homepage</label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-adm btn-adm-primary btn-adm-lg">
                <i class="bi bi-cloud-upload"></i> Upload
            </button>
        </form>

    </div>
</div>
