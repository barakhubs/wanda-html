<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/portfolio/');
    exit;
}
verifyCsrf();

$id    = (int)($_POST['id'] ?? 0);
$model = new Portfolio();
$item  = $model->getById($id);

if (!$item) {
    flashMessage('error', 'Item not found.');
    header('Location: ' . BASE_URL . '/admin/portfolio/');
    exit;
}

if ($item['thumbnail'] && str_starts_with($item['thumbnail'], 'uploads/')) {
    deleteUpload($item['thumbnail']);
}

$model->delete($id);
flashMessage('success', 'Portfolio item deleted.');
header('Location: ' . BASE_URL . '/admin/portfolio/');
exit;
