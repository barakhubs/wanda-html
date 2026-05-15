<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-people-fill"></i> User Management</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/users/create" class="btn-adm btn-adm-primary">
                <i class="bi bi-plus-lg"></i>
                <span class="btn-label">Add Member</span>
            </a>
        </div>
    </div>

    <div class="admin-content">

        <?php if ($flash) : ?>
            <div class="alert alert-<?= e($flash['type']) ?>">
                <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-title">
                <span><i class="bi bi-people"></i> All Accounts</span>
                <span style="font-size:.8rem;font-weight:400;color:var(--admin-muted)"><?= count($users) ?> user<?= count($users) !== 1 ? 's' : '' ?></span>
            </div>

            <?php if (empty($users)) : ?>
                <p style="color:var(--admin-muted);text-align:center;padding:2rem 0">No user accounts found.</p>
            <?php else : ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th style="text-align:right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u) : ?>
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:.6rem">
                                            <?php if (!empty($u['avatar'])) : ?>
                                                <img src="<?= BASE_URL . '/' . e($u['avatar']) ?>"
                                                    alt=""
                                                    style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:1px solid var(--admin-border)">
                                            <?php else : ?>
                                                <span class="user-avatar-icon"><i class="bi bi-person-fill"></i></span>
                                            <?php endif; ?>
                                            <strong><?= e($u['username']) ?></strong>
                                        </div>
                                    </td>
                                    <td><?= e($u['full_name'] ?: '—') ?></td>
                                    <td><?= e($u['email'] ?: '—') ?></td>
                                    <td>
                                        <?php if ($u['role'] === 'admin') : ?>
                                            <span class="badge badge-primary">Super Admin</span>
                                        <?php else : ?>
                                            <span class="badge badge-muted">Member</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="white-space:nowrap;font-size:.85rem;color:var(--admin-muted)">
                                        <?= e(date('d M Y', strtotime($u['created_at']))) ?>
                                    </td>
                                    <td style="text-align:right;white-space:nowrap">
                                        <?php if ($u['role'] === 'member') : ?>
                                            <a href="<?= BASE_URL ?>/admin/users/edit/<?= $u['id'] ?>"
                                                class="btn-adm btn-adm-sm btn-adm-outline">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="post"
                                                action="<?= BASE_URL ?>/admin/users/delete/<?= $u['id'] ?>"
                                                style="display:inline"
                                                onsubmit="return confirm('Delete member \'<?= e(addslashes($u['username'])) ?>\'? This cannot be undone.')">
                                                <?= csrfField() ?>
                                                <button type="submit" class="btn-adm btn-adm-sm btn-adm-danger">
                                                    <i class="bi bi-trash3"></i> Delete
                                                </button>
                                            </form>
                                        <?php else : ?>
                                            <span style="font-size:.8rem;color:var(--admin-muted)">Protected</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Permissions reference card -->
        <div class="admin-card">
            <div class="admin-card-title"><i class="bi bi-shield-check"></i> Role Permissions</div>
            <div style="overflow-x:auto">
                <table class="admin-table" style="font-size:.88rem">
                    <thead>
                        <tr>
                            <th>Area</th>
                            <th style="text-align:center">Super Admin</th>
                            <th style="text-align:center">Member</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $perms = [
                            ['Dashboard',        true, true],
                            ['Blog Posts',       true, true],
                            ['Portfolio',        true, true],
                            ['Team Members',     true, true],
                            ['Testimonials',     true, true],
                            ['Home Gallery',     true, true],
                            ['Reports',          true, true],
                            ['My Profile',       true, true],
                            ['Site Settings',    true, false],
                            ['User Management',  true, false],
                        ];
                        foreach ($perms as [$area, $admin, $member]) :
                        ?>
                            <tr>
                                <td><?= e($area) ?></td>
                                <td style="text-align:center">
                                    <i class="bi bi-check-circle-fill" style="color:var(--admin-success)"></i>
                                </td>
                                <td style="text-align:center">
                                    <?php if ($member) : ?>
                                        <i class="bi bi-check-circle-fill" style="color:var(--admin-success)"></i>
                                    <?php else : ?>
                                        <i class="bi bi-x-circle-fill" style="color:var(--admin-danger)"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- .admin-content -->
</div><!-- .admin-main -->

<style>
    .user-avatar-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: var(--admin-muted);
        flex-shrink: 0;
    }
</style>