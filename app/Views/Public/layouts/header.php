<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Security::e($title ?? Env::get('APP_NAME', 'CMS')) ?></title>
</head>
<body>
    <header>
        <nav>
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>">Beranda</a> |
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin">Login Admin</a>
        </nav>
        <hr>
    </header>
    <main>
