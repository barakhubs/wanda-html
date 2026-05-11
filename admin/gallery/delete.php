<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/gallery/');
    exit;
}
verifyCsrf();

$id    = (int)($_POST['id'] ?? 0);
$model = new HomeGallery();
$img   = $model->getById($id);

if (!$img) {
    flashMessage('error', 'Image not found.');
    header('Location: ' . BASE_URL . '/admin/gallery/');
    exit;
}

// Delete file (gallery images are always uploaded, not static)
if ($img['image_path']) {
    deleteUpload($img['image_path']);
}

$model->delete($id);
flashMessage('success', 'Image deleted.');
header('Location: ' . BASE_URL . '/admin/gallery/');
exit;
