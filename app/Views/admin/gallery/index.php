<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-grid"></i> Home Gallery</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/gallery/create" class="btn-adm btn-adm-primary">
                <i class="bi bi-cloud-upload"></i> Upload Image
            </a>
        </div>
    </div>
    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-title">Gallery Images (<?= count($images) ?>)</div>
            <?php if (empty($images)) : ?>
                <p style="color:var(--admin-muted)">No gallery images yet. <a href="<?= BASE_URL ?>/admin/gallery/create">Upload one.</a></p>
            <?php else : ?>
                <div class="admin-gallery-grid">
                    <?php foreach ($images as $img) : ?>
                        <div class="admin-gallery-item">
                            <img src="<?= e(BASE_URL . '/' . $img['image_path']) ?>" alt="<?= e($img['alt_text']) ?>">
                            <?php if ($img['is_wide']) : ?>
                                <span class="badge badge-primary" style="position:absolute;top:6px;left:6px;font-size:.65rem">Wide</span>
                            <?php endif; ?>
                            <?php if (!$img['published']) : ?>
                                <span class="badge badge-muted" style="position:absolute;top:6px;right:6px;font-size:.65rem">Hidden</span>
                            <?php endif; ?>
                            <form method="post" action="<?= BASE_URL ?>/admin/gallery/delete/<?= $img['id'] ?>" class="gallery-delete"
                                onsubmit="return confirm('Delete this image?')">
                                <?= csrfField() ?>
                                <button type="submit" class="btn-adm btn-adm-danger btn-adm-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
