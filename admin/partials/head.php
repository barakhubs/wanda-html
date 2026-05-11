<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminPageTitle ?? 'Admin') ?> | Wanda Admin</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/css/admin.css">
    <?= $adminExtraHead ?? '' ?>
</head>

<body>
    <div class="admin-wrapper">