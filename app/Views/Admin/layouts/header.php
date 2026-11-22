<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Security::e($title ?? 'Admin Panel - ' . Env::get('APP_NAME', 'Almuna CMS')) ?></title>
    <?php $baseAsset = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
	<link rel="stylesheet" href="<?= $baseAsset ?>/assets/css/admin.css">
    
</head>
<body>
    <header>
        <strong>Panel Admin CMS</strong> |
        <nav style="display:inline;">
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin">Dashboard</a> |
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/artikel">Artikel</a> |
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>" target="_blank">Lihat Web</a> |
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/user">Pengguna</a> |
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/auth/logout">Logout</a>
        </nav>
        <hr>
    </header>
    <main>