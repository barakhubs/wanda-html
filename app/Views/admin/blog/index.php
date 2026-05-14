<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-journal-text"></i> Blog Posts</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/blog/create" class="btn-adm btn-adm-primary">
                <i class="bi bi-plus-lg"></i> New Post
            </a>
        </div>
    </div>
    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-title">All Posts (<?= $pagination['total'] ?>)</div>
            <?php if (empty($posts)) : ?>
                <p style="color:var(--admin-muted)">No blog posts yet. <a href="<?= BASE_URL ?>/admin/blog/create">Create one.</a></p>
            <?php else : ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post) : ?>
                                <tr>
                                    <td class="thumb-cell">
                                        <?php if ($post['thumbnail']) : ?>
                                            <img src="<?= e(BASE_URL . '/' . $post['thumbnail']) ?>" alt="">
                                        <?php else : ?>
                                            <span style="color:var(--admin-muted);font-size:.8rem">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($post['title']) ?></td>
                                    <td><span class="badge badge-primary"><?= e(ucfirst($post['category'])) ?></span></td>
                                    <td>
                                        <?= $post['published']
                                            ? '<span class="badge badge-success">Published</span>'
                                            : '<span class="badge badge-muted">Draft</span>' ?>
                                    </td>
                                    <td><?= e(date('d M Y', strtotime($post['created_at']))) ?></td>
                                    <td style="white-space:nowrap">
                                        <a href="<?= BASE_URL ?>/blog/<?= e($post['slug']) ?>" target="_blank" class="btn-adm btn-adm-outline btn-adm-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/admin/blog/edit/<?= $post['id'] ?>" class="btn-adm btn-adm-outline btn-adm-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="<?= BASE_URL ?>/admin/blog/delete/<?= $post['id'] ?>" style="display:inline"
                                            onsubmit="return confirm('Delete this post?')">
                                            <?= csrfField() ?>
                                            <button type="submit" class="btn-adm btn-adm-danger btn-adm-sm" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?= paginationHtml($pagination, BASE_URL . '/admin/blog') ?>
        </div>