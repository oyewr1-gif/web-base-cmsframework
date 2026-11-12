<h2>Daftar Artikel CMS</h2>
<p><a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/post/create">+ Tambah Artikel Baru</a></p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Artikel</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($items)): ?>
            <tr><td colspan="4" align="center">Belum ada artikel.</td></tr>
        <?php else: ?>
            <?php foreach ($items as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= Security::e($row['judul'] ?? '') ?></td>
                    <td><?= Security::e($row['status'] ?? 'draft') ?></td>
                    <td>
                        <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/post/edit/<?= (int)$row['id'] ?>">Edit</a> |
                        <form method="POST" action="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/post/delete/<?= (int)$row['id'] ?>" style="display:inline;" onsubmit="return confirm('Hapus artikel ini?')">
                            <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
