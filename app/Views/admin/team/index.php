<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-people"></i> Team Members</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/team/create" class="btn-adm btn-adm-primary">
                <i class="bi bi-plus-lg"></i> Add Member
            </a>
        </div>
    </div>
    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-title">All Members (<?= count($members) ?>)</div>
            <?php if (empty($members)) : ?>
                <p style="color:var(--admin-muted)">No team members yet. <a href="<?= BASE_URL ?>/admin/team/create">Add one.</a></p>
            <?php else : ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $m) : ?>
                                <tr>
                                    <td class="thumb-cell">
                                        <?php if ($m['photo']) : ?>
                                            <img src="<?= e(BASE_URL . '/' . $m['photo']) ?>" alt="" style="border-radius:50%;width:44px;height:44px;object-fit:cover">
                                        <?php else : ?>
                                            <div style="width:44px;height:44px;border-radius:50%;background:<?= e($m['gradient_css']) ?>;display:flex;align-items:center;justify-content:center">
                                                <i class="bi <?= e($m['fallback_icon'] ?? 'bi-person-fill') ?>" style="color:#fff"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($m['name']) ?></td>
                                    <td><?= e($m['role']) ?></td>
                                    <td>
                                        <?= $m['published']
                                            ? '<span class="badge badge-success">Visible</span>'
                                            : '<span class="badge badge-muted">Hidden</span>' ?>
                                    </td>
                                    <td style="white-space:nowrap">
                                        <a href="<?= BASE_URL ?>/admin/team/edit/<?= $m['id'] ?>" class="btn-adm btn-adm-outline btn-adm-sm"><i class="bi bi-pencil"></i></a>
                                        <form method="post" action="<?= BASE_URL ?>/admin/team/delete/<?= $m['id'] ?>" style="display:inline"
                                            onsubmit="return confirm('Delete this team member?')">
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
        </div>

    </div>
</div>
