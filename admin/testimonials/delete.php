<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/testimonials/');
    exit;
}
verifyCsrf();

$id    = (int)($_POST['id'] ?? 0);
$model = new Testimonial();

if (!$model->getById($id)) {
    flashMessage('error', 'Testimonial not found.');
    header('Location: ' . BASE_URL . '/admin/testimonials/');
    exit;
}

$model->delete($id);
flashMessage('success', 'Testimonial deleted.');
header('Location: ' . BASE_URL . '/admin/testimonials/');
exit;
