<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-pencil"></i> Edit Report</h1>
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
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">PDF File</div>

                <?php if (!empty($data['pdf_path'])) : ?>
                    <div style="margin-bottom:1rem;display:flex;align-items:center;gap:.75rem">
                        <i class="bi bi-filetype-pdf" style="font-size:1.8rem;color:#dc3545"></i>
                        <div>
                            <div style="font-size:.85rem;color:var(--admin-muted)">Current file:</div>
                            <a href="<?= e(BASE_URL . '/' . $data['pdf_path']) ?>" target="_blank"
                                style="font-size:.9rem;word-break:break-all">
                                <?= e(basename($data['pdf_path'])) ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="pdf_file">Replace PDF (leave blank to keep current)</label>
                    <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf">
                    <span class="form-hint">Upload a new PDF only if you want to replace the existing file. Max 10 MB.</span>
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
                <i class="bi bi-save"></i> Update Report
            </button>
        </form>

    </div>
</div>