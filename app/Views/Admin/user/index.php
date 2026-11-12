<h2>Manajemen Pengguna (User)</h2>
<p><a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/user/create">+ Tambah User Baru</a></p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Email</th>
            <th>Peran (Role)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($items)): ?>
            <tr><td colspan="5" align="center">Data pengguna tidak ditemukan.</td></tr>
        <?php else: ?>
            <?php foreach ($items as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= Security::e($row['username'] ?? '') ?></td>
                    <td><?= Security::e($row['email'] ?? '') ?></td>
                    <td><?= Security::e($row['role'] ?? '-') ?></td>
                    <td>
                        <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/user/edit/<?= (int)$row['id'] ?>">Edit</a> |
                        <form method="POST" action="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/user/delete/<?= (int)$row['id'] ?>" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                            <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
