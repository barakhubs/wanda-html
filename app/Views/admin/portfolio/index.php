<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-images"></i> Portfolio Items</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/portfolio/create" class="btn-adm btn-adm-primary">
                <i class="bi bi-plus-lg"></i> New Item
            </a>
        </div>
    </div>
    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-title">All Portfolio Items (<?= $pagination['total'] ?>)</div>
            <?php if (empty($items)) : ?>
                <p style="color:var(--admin-muted)">No portfolio items yet. <a href="<?= BASE_URL ?>/admin/portfolio/create">Add one.</a></p>
            <?php else : ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Thumb</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Featured</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item) : ?>
                                <tr>
                                    <td class="thumb-cell">
                                        <?php if ($item['thumbnail']) : ?>
                                            <img src="<?= e(BASE_URL . '/' . $item['thumbnail']) ?>" alt="">
                                        <?php else : ?>
                                            <div style="width:56px;height:42px;border-radius:4px;background:<?= e($item['gradient_css']) ?>;display:flex;align-items:center;justify-content:center">
                                                <i class="bi <?= e($item['icon_class']) ?>" style="color:#fff;font-size:.9rem"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($item['title']) ?></td>
                                    <td><span class="badge badge-primary"><?= e(ucfirst($item['category'])) ?></span></td>
                                    <td><?= $item['featured'] ? '<span class="badge badge-warning">Featured</span>' : '—' ?></td>
                                    <td>
                                        <?= $item['published']
                                            ? '<span class="badge badge-success">Live</span>'
                                            : '<span class="badge badge-muted">Hidden</span>' ?>
                                    </td>
                                    <td style="white-space:nowrap">
                                        <a href="<?= BASE_URL ?>/admin/portfolio/edit/<?= $item['id'] ?>" class="btn-adm btn-adm-outline btn-adm-sm"><i class="bi bi-pencil"></i></a>
                                        <form method="post" action="<?= BASE_URL ?>/admin/portfolio/delete/<?= $item['id'] ?>" style="display:inline"
                                            onsubmit="return confirm('Delete this portfolio item?')">
                                            <?= csrfField() ?>
                                            <button type="submit" class="btn-adm btn-adm-danger btn-adm-sm"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?= paginationHtml($pagination, BASE_URL . '/admin/portfolio') ?>
        </div>