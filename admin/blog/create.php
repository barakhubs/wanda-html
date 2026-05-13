<?php
require_once __DIR__ . '/../auth.php';

$errors = [];
$data   = [
    'title'     => '',
    'category'  => BLOG_CATEGORIES[0],
    'excerpt'   => '',
    'body'      => '',
    'read_time' => 3,
    'published' => 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $data['title']     = trim($_POST['title'] ?? '');
    $rawCat            = $_POST['category'] ?? '';
    $data['category']  = in_array($rawCat, BLOG_CATEGORIES, true) ? $rawCat : BLOG_CATEGORIES[0];
    $data['excerpt']   = trim($_POST['excerpt'] ?? '');
    $data['body']      = $_POST['body'] ?? '';
    $data['read_time'] = (int)($_POST['read_time'] ?? 3);
    $data['published'] = isset($_POST['published']) ? 1 : 0;

    if ($data['title'] === '') $errors[] = 'Title is required.';
    if ($data['body'] === '')  $errors[] = 'Body is required.';

    $thumbnail = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        try {
            $thumbnail = handleUpload($_FILES['thumbnail'], 'blog');
        } catch (RuntimeException $e) {
            $errors[] = 'Image upload failed: ' . $e->getMessage();
        }
    }

    if (empty($errors)) {
        $db         = Database::getInstance();
        $blogModel  = new BlogPost();
        $slug       = uniqueSlug($db, generateSlug($data['title']), 'blog_posts');

        $blogModel->create([
            'title'     => $data['title'],
            'slug'      => $slug,
            'category'  => $data['category'],
            'excerpt'   => $data['excerpt'],
            'body'      => $data['body'],
            'thumbnail' => $thumbnail,
            'read_time' => $data['read_time'],
            'published' => $data['published'],
        ]);

        flashMessage('success', 'Blog post created successfully.');
        header('Location: ' . BASE_URL . '/admin/blog/');
        exit;
    }
}

$categories = BLOG_CATEGORIES;

require_once ROOT_PATH . '/admin/partials/quill-init.php';
$adminPageTitle    = 'New Blog Post';
$adminExtraHead    = QUILL_HEAD_CSS;
$adminExtraScripts = QUILL_INIT_JS;

require_once ROOT_PATH . '/admin/partials/head.php';
require_once ROOT_PATH . '/admin/partials/sidebar.php';
?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1><i class="bi bi-plus-lg"></i> New Blog Post</h1>
        <div class="admin-topbar-actions">
            <a href="<?= BASE_URL ?>/admin/blog/" class="btn-adm btn-adm-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="admin-content">

        <?php if ($errors) : ?>
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
                <div class="admin-card-title">Post Content</div>

                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?= e($data['title']) ?>" required>
                </div>

                <div class="form-row col-2">
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category">
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= e($cat) ?>" <?= $data['category'] === $cat ? 'selected' : '' ?>><?= e(ucfirst($cat)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="read_time">Read Time (minutes)</label>
                        <input type="number" id="read_time" name="read_time" min="1" max="60" value="<?= $data['read_time'] ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="excerpt">Excerpt / Summary</label>
                    <textarea id="excerpt" name="excerpt" rows="2"><?= e($data['excerpt']) ?></textarea>
                    <span class="form-hint">Short description shown in blog listing and social shares.</span>
                </div>

                <div class="form-group">
                    <label>Body *</label>
                    <div id="editor"><?= $data['body'] ?></div>
                    <input type="hidden" id="body-hidden" name="body" value="<?= e($data['body']) ?>">
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Thumbnail</div>
                <div class="form-group">
                    <label for="thumbnail">Upload Image (JPEG/PNG/WebP, max 5 MB)</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-title">Publish</div>
                <div class="form-check">
                    <input type="checkbox" id="published" name="published" value="1" <?= $data['published'] ? 'checked' : '' ?>>
                    <label for="published">Publish this post (visible on website)</label>
                </div>
            </div>

            <button type="submit" class="btn-adm btn-adm-primary btn-adm-lg">
                <i class="bi bi-save"></i> Save Post
            </button>
        </form>

    </div>
</div>

<?php require_once ROOT_PATH . '/admin/partials/foot.php'; ?>