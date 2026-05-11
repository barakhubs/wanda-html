<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/team/');
    exit;
}
verifyCsrf();

$id     = (int)($_POST['id'] ?? 0);
$model  = new TeamMember();
$member = $model->getById($id);

if (!$member) {
    flashMessage('error', 'Member not found.');
    header('Location: ' . BASE_URL . '/admin/team/');
    exit;
}

if ($member['photo'] && str_starts_with($member['photo'], 'uploads/')) {
    deleteUpload($member['photo']);
}

$model->delete($id); // cascade deletes team_skills
flashMessage('success', 'Team member deleted.');
header('Location: ' . BASE_URL . '/admin/team/');
exit;
