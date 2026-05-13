<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-plus-lg"></i> New Report</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/reports" class="btn-adm btn-adm-outline">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="admin-content">

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.2rem">
                    <?php foreach ($errors as $err) : ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="admin-form">
            <?= csrfField() ?>

            <div class="admin-card">
                <div class="admin-card-title">Report Details</div>

                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?= e($data['title']) ?>" required>
                </div>

                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category">
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= e($cat) ?>" <?= $data['category'] === $cat ? 'selected' : '' ?>>
                                    <?= e(ucfirst($cat)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="excerpt">Excerpt / Summary *</label>
                    <textarea id="excerpt" name="excerpt" rows="3" required><?= e($data['excerpt']) ?></textarea>
                    <span class="form-hint">Briefly describe the report&#8202;—&#8202;shown on the listing page.</span>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">PDF File *</div>
                <div class="form-group">
                    <label for="pdf_file">Upload PDF (max 5 MB)</label>
                    <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf" required>
                    <span class="form-hint">Only PDF files accepted. The file will be served inline (read-only, no forced download).</span>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Publish</div>
                <div class="form-check">
                    <input type="checkbox" id="published" name="published" value="1"
                           <?= $data['published'] ? 'checked' : '' ?>>
                    <label for="published">Publish this report (visible on website)</label>
                </div>
            </div>

            <button type="submit" class="btn-adm btn-adm-primary btn-adm-lg">
                <i class="bi bi-save"></i> Save Report
            </button>
        </form>

    </div>
</div>
