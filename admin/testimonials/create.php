<?php
require_once __DIR__ . '/../auth.php';

$errors = [];
$data   = ['quote' => '', 'author_initials' => '', 'author_role' => '', 'author_org' => '', 'sort_order' => 0, 'published' => 1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $data['quote']           = trim($_POST['quote'] ?? '');
    $data['author_initials'] = trim($_POST['author_initials'] ?? '');
    $data['author_role']     = trim($_POST['author_role'] ?? '');
    $data['author_org']      = trim($_POST['author_org'] ?? '');
    $data['sort_order']      = (int)($_POST['sort_order'] ?? 0);
    $data['published']       = isset($_POST['published']) ? 1 : 0;

    if ($data['quote'] === '')           $errors[] = 'Quote is required.';
    if ($data['author_initials'] === '') $errors[] = 'Author initials are required.';

    if (empty($errors)) {
        (new Testimonial())->create($data);
        flashMessage('success', 'Testimonial added.');
        header('Location: ' . BASE_URL . '/admin/testimonials/');
        exit;
    }
}

$adminPageTitle = 'Add Testimonial';
require_once ROOT_PATH . '/admin/partials/head.php';
require_once ROOT_PATH . '/admin/partials/sidebar.php';
?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-plus-lg"></i> Add Testimonial</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/testimonials/" class="btn-adm btn-adm-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="admin-content">
        <?php if ($errors) : ?>
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.2rem">
                    <?php foreach ($errors as $err) : ?><li><?= e($err) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="admin-form">
            <?= csrfField() ?>
            <div class="admin-card">
                <div class="admin-card-title">Testimonial</div>
                <div class="form-group">
                    <label for="quote">Quote *</label>
                    <textarea id="quote" name="quote" rows="4"><?= e($data['quote']) ?></textarea>
                </div>
                <div class="form-row col-3">
                    <div class="form-group">
                        <label for="author_initials">Author Initials / Name *</label>
                        <input type="text" id="author_initials" name="author_initials" value="<?= e($data['author_initials']) ?>" placeholder="J.D.">
                    </div>
                    <div class="form-group">
                        <label for="author_role">Author Role</label>
                        <input type="text" id="author_role" name="author_role" value="<?= e($data['author_role']) ?>" placeholder="Director">
                    </div>
                    <div class="form-group">
                        <label for="author_org">Organisation</label>
                        <input type="text" id="author_org" name="author_org" value="<?= e($data['author_org']) ?>" placeholder="UNICEF Uganda">
                    </div>
                </div>
                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" value="<?= (int)$data['sort_order'] ?>" min="0">
                    </div>
                    <div class="form-group" style="align-self:flex-end;padding-bottom:.3rem">
                        <div class="form-check">
                            <input type="checkbox" id="published" name="published" value="1" <?= $data['published'] ? 'checked' : '' ?>>
                            <label for="published">Published</label>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-adm btn-adm-primary btn-adm-lg"><i class="bi bi-save"></i> Save</button>
        </form>
    </div>
</div>

<?php require_once ROOT_PATH . '/admin/partials/foot.php'; ?>