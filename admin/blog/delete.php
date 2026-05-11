<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/blog/');
    exit;
}

verifyCsrf();

$id        = (int)($_POST['id'] ?? 0);
$blogModel = new BlogPost();
$post      = $blogModel->getById($id);

if (!$post) {
    flashMessage('error', 'Post not found.');
    header('Location: ' . BASE_URL . '/admin/blog/');
    exit;
}

// Delete upload if it's in /uploads/
if ($post['thumbnail'] && str_starts_with($post['thumbnail'], 'uploads/')) {
    deleteUpload($post['thumbnail']);
}

$blogModel->delete($id);
flashMessage('success', 'Blog post deleted.');
header('Location: ' . BASE_URL . '/admin/blog/');
exit;
