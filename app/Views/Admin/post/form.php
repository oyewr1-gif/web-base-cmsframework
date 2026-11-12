<?php
$isEdit = ($action ?? 'create') === 'edit';
$judul  = $item['judul'] ?? ($old['judul'] ?? '');
$konten = $item['konten'] ?? ($old['konten'] ?? '');
$status = $item['status'] ?? ($old['status'] ?? 'draft');
?>

<h2><?= $isEdit ? 'Edit Artikel' : 'Tulis Artikel Baru' ?></h2>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
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
        <label>Judul Artikel:</label><br>
        <input type="text" name="judul" value="<?= Security::e($judul) ?>" required style="width: 100%;">
    </div><br>

    <div>
        <label>Isi Konten:</label><br>
        <textarea name="konten" rows="10" style="width: 100%;"><?= htmlspecialchars($konten, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div><br>

    <div>
        <label>Status Publikasi:</label><br>
        <select name="status">
            <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="publish" <?= $status === 'publish' ? 'selected' : '' ?>>Publish</option>
        </select>
    </div><br>

    <button type="submit"><?= $isEdit ? 'Perbarui Artikel' : 'Simpan Artikel' ?></button>
    <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/post">Batal</a>
</form>
