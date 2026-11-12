<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin CMS</title>
</head>
<body>
    <h2>Login Pengelola CMS</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= Security::e($error) ?></p>
    <?php endif; ?>

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
</body>
</html>
