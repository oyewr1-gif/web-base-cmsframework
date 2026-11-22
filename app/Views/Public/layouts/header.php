<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Security::e($title ?? Env::get('APP_NAME', 'CMS')) ?></title>
    <!--<link rel="stylesheet" href="<?= ROOT_PATH ?>public/assets/css/public.css">-->
    <?php $baseAsset = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
	<link rel="stylesheet" href="<?= $baseAsset ?>/assets/css/public.css">

    
</head>
<body>
    <header>
        <nav>
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>">Beranda</a> |
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/artikel">Artikel</a> |
            <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin">Login Admin</a>
        </nav>
        <hr>
    </header>
    <main>
    	<!--	<?= $baseAsset ?>
    	<?= ROOT_PATH ?>public/assets/css/public.css
	<?= rtrim(Env::get('APP_URL'), '/') ?>/public/assets/css/public.css -->