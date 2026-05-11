<?php
require_once __DIR__ . '/../auth.php';

$model = new Testimonial();
$all   = $model->getAll();

$adminPageTitle = 'Testimonials';
$flash = getFlash();
require_once ROOT_PATH . '/admin/partials/head.php';
require_once ROOT_PATH . '/admin/partials/sidebar.php';
?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-chat-quote"></i> Testimonials</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/testimonials/create.php" class="btn-adm btn-adm-primary">
                <i class="bi bi-plus-lg"></i> Add Testimonial
            </a>
        </div>
    </div>
    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-title">All Testimonials (<?= count($all) ?>)</div>
            <?php if (empty($all)) : ?>
                <p style="color:var(--admin-muted)">No testimonials yet. <a href="<?= BASE_URL ?>/admin/testimonials/create.php">Add one.</a></p>
            <?php else : ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Quote (excerpt)</th>
                                <th>Author</th>
                                <th>Organisation</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all as $t) : ?>
                                <tr>
                                    <td><?= e(mb_strimwidth($t['quote'], 0, 80, '…')) ?></td>
                                    <td><?= e($t['author_initials']) ?></td>
                                    <td><?= e($t['author_org']) ?></td>
                                    <td>
                                        <?= $t['published']
                                            ? '<span class="badge badge-success">Published</span>'
                                            : '<span class="badge badge-muted">Hidden</span>' ?>
                                    </td>
                                    <td style="white-space:nowrap">
                                        <a href="<?= BASE_URL ?>/admin/testimonials/edit.php?id=<?= $t['id'] ?>" class="btn-adm btn-adm-outline btn-adm-sm"><i class="bi bi-pencil"></i></a>
                                        <form method="post" action="<?= BASE_URL ?>/admin/testimonials/delete.php" style="display:inline"
                                            onsubmit="return confirm('Delete this testimonial?')">
                                            <?= csrfField() ?>
                                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                            <button type="submit" class="btn-adm btn-adm-danger btn-adm-sm"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once ROOT_PATH . '/admin/partials/foot.php'; ?>