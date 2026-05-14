<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-file-earmark-text"></i> Reports</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/reports/create" class="btn-adm btn-adm-primary">
                <i class="bi bi-plus-lg"></i> Add Report
            </a>
        </div>
    </div>

    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-title">All Reports (<?= $pagination['total'] ?>)</div>
            <?php if (empty($reports)) : ?>
                <p style="color:var(--admin-muted)">No reports yet. <a href="<?= BASE_URL ?>/admin/reports/create">Upload one.</a></p>
            <?php else : ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report) : ?>
                                <tr>
                                    <td>
                                        <strong><?= e($report['title']) ?></strong>
                                        <br><small style="color:var(--admin-muted)"><?= e(mb_strimwidth($report['excerpt'], 0, 90, '…')) ?></small>
                                    </td>
                                    <td><span class="badge badge-primary"><?= e(ucfirst($report['category'])) ?></span></td>
                                    <td>
                                        <?= $report['published']
                                            ? '<span class="badge badge-success">Published</span>'
                                            : '<span class="badge badge-muted">Draft</span>' ?>
                                    </td>
                                    <td><?= e(date('d M Y', strtotime($report['created_at']))) ?></td>
                                    <td style="white-space:nowrap">
                                        <a href="<?= BASE_URL ?>/reports/<?= e($report['slug']) ?>" target="_blank"
                                            class="btn-adm btn-adm-outline btn-adm-sm" title="View public page">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL . '/public/' . e($report['pdf_path']) ?>" target="_blank"
                                            class="btn-adm btn-adm-outline btn-adm-sm" title="Open PDF">
                                            <i class="bi bi-filetype-pdf"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/admin/reports/edit/<?= $report['id'] ?>"
                                            class="btn-adm btn-adm-outline btn-adm-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="post" action="<?= BASE_URL ?>/admin/reports/delete/<?= $report['id'] ?>"
                                            style="display:inline" onsubmit="return confirm('Delete this report and its PDF?')">
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
            <?= paginationHtml($pagination, BASE_URL . '/admin/reports') ?>
        </div>