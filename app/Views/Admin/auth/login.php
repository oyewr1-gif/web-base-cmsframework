<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin CMS</title>
    <?php $baseAsset = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
    <link rel="stylesheet" href="<?= $baseAsset ?>/assets/css/admin.css">
</head>
<body>
    <h2>Login Pengelola CMS</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= Security::e($error) ?></p>
    <?php endif; ?>

    <div class="login-box">
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
        <div>
            <label>Username:</label><br>
            <input type="text" name="username" required>
        </div><br>
        <div>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div><br>
        <button type="submit">Masuk</button>
    </form>
    </div>
</body>
</html>
