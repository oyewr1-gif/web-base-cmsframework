<?php
$isEdit   = ($action ?? 'create') === 'edit';
$username = $item['username'] ?? ($old['username'] ?? '');
$email    = $item['email'] ?? ($old['email'] ?? '');
$role     = $item['role'] ?? ($old['role'] ?? 'editor');
?>

<h2><?= $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna Baru' ?></h2>

<?php if (!empty($errors)): ?>
    <div style="color: red; margin-bottom: 15px;">
        <strong>Terjadi Kesalahan:</strong>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= Security::e(is_array($err) ? implode(', ', $err) : $err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">

    <div>
        <label>Username (minimal 4 karakter):</label><br>
        <input type="text" name="username" value="<?= Security::e($username) ?>" minlength="4" required>
    </div><br>

    <div>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= Security::e($email) ?>" required>
    </div><br>

    <div>
        <label>Password <?= $isEdit ? '(kosongkan jika tidak ingin mengubah)' : '(minimal 6 karakter)' ?>:</label><br>
        <input type="password" name="password" minlength="6" <?= $isEdit ? '' : 'required' ?>>
    </div><br>

    <div>
        <label>Peran (Role):</label><br>
        <select name="role">
            <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="editor" <?= $role === 'editor' ? 'selected' : '' ?>>Editor</option>
            <option value="author" <?= $role === 'author' ? 'selected' : '' ?>>Author</option>
        </select>
    </div><br>

    <button type="submit"><?= $isEdit ? 'Perbarui User' : 'Simpan User' ?></button>
    <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/user">Batal</a>
</form>
