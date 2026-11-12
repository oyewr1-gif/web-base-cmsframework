# Panduan Penambahan Modul Baru ( CMS Framework)

Dokumentasi ini menjelaskan standar pembuatan modul baru pada ** CMS Front-End Framework** dengan memanfaatkan arsitektur **Modular MVC** (`Public` dan `Admin`) serta sistem **Centralized CRUD** (`CoreController` dan `CoreModule`).

---

## 1. Konvensi Penamaan & Struktur Folder

Sebelum membuat modul baru, perhatikan aturan berikut agar *autoloader* (`app/init.php`) dan *router* (`app/Core/App.php`) dapat mengenali modul secara otomatis:

* **Tentukan Area Modul:**
  * Gunakan folder `Admin` (`app/Modules/Admin/` & `app/Views/Admin/`) untuk fitur pengelolaan konten yang membutuhkan otentikasi JWT dan diakses melalui awalan URL `/admin/{nama_modul}`.
  * Gunakan folder `Public` (`app/Modules/Public/` & `app/Views/Public/`) untuk halaman pengunjung yang diakses langsung melalui URL `/{nama_modul}`.
* **Penamaan Kelas & Folder:** Nama folder modul harus diawali huruf kapital (PascalCase), diikuti nama kelas `{NamaModul}Module` dan `{NamaModul}Controller`.
* **Keunikan Nama Kelas:** Pastikan tidak ada dua kelas dengan nama yang sama persis antara folder `Public` dan `Admin`.

---

## 2. Skenario A: Menambah Modul di Area Admin (Contoh: `Kategori`)

Misalkan Anda ingin membuat modul **Kategori** untuk mengelola kategori artikel melalui *endpoint* REST API `/kategori` dengan rute browser `/admin/kategori`.

### Langkah 1: Siapkan Struktur Direktori
Buat folder modul dan tampilan pada jalur berikut:

```text
app/
├── Modules/
│   └── Admin/
│       └── Kategori/
│           ├── KategoriModule.php
│           └── KategoriController.php
└── Views/
    └── Admin/
        └── kategori/
            ├── index.php
            └── form.php
```

### Langkah 2: Buat Kelas Module (`KategoriModule.php`)
Buat file `app/Modules/Admin/Kategori/KategoriModule.php` yang mewarisi `CoreModule`. Atur `$endpoint` sesuai *endpoint* di REST API dan aktifkan `$authRules` untuk menyertakan *header* `Authorization: Bearer <jwt_token>`.

```php
<?php
class KategoriModule extends CoreModule
{
    protected \$endpoint = '/kategori';
    
    // Wajib kirim token JWT untuk seluruh operasi CRUD di area Admin
    protected \$authRules = [
        'index'  => true,
        'detail' => true,
        'store'  => true,
        'update' => true,
        'delete' => true
    ];
}
```

### Langkah 3: Buat Kelas Controller (`KategoriController.php`)
Buat file `app/Modules/Admin/Kategori/KategoriController.php` yang mewarisi `CoreController`. Seluruh logika `index()`, `create()`, `edit($id)`, dan `delete($id)` sudah otomatis tersedia dari kelas induk. Anda hanya perlu mengatur properti konfigurasi dan metode `mapPayload()`.

```php
<?php
class KategoriController extends CoreController
{
    protected \$area = 'Admin';
    protected \$viewPath = 'kategori';
    protected \$redirectPath = '/admin/kategori';

    public function __construct()
    {
        \$this->module = new KategoriModule();
    }

    /**
     * Memetakan input form yang sudah dibersihkan ke struktur payload JSON API (\$fillable)
     */
    protected function mapPayload(array \$cleanPost)
    {
        return [
            'nama_kategori' => \$cleanPost['nama_kategori'] ?? '',
            'slug'          => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', \$cleanPost['nama_kategori'] ?? ''), '-'))
        ];
    }
}
```

### Langkah 4: Buat Tampilan Daftar Data (`app/Views/Admin/kategori/index.php`)
File tampilan tidak perlu menyertakan tag `<html>`, `<head>`, atau `<body>` karena otomatis dibungkus oleh `app/Views/Admin/layouts/header.php` dan `footer.php`.

```php
<h2>Manajemen Kategori</h2>
<p><a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/kategori/create">+ Tambah Kategori</a></p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Slug</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty(\$items)): ?>
            <tr><td colspan="4" align="center">Belum ada data kategori.</td></tr>
        <?php else: ?>
            <?php foreach (\$items as i => row): ?>
                <tr>
                    <td><?= \$i + 1 ?></td>
                    <td><?= Security::e(\$row['nama_kategori'] ?? '') ?></td>
                    <td><?= Security::e(\$row['slug'] ?? '') ?></td>
                    <td>
                        <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/kategori/edit/<?= (int)\$row['id'] ?>">Edit</a> |
                        <form method="POST" action="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/kategori/delete/<?= (int)\$row['id'] ?>" style="display:inline;" onsubmit="return confirm('Hapus kategori ini?')">
                            <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
```

### Langkah 5: Buat Tampilan Form Tambah & Edit (`app/Views/Admin/kategori/form.php`)
Gunakan satu file `form.php` untuk menangani proses `create` maupun `edit`.

```php
<?php
isEdit = (action ?? 'create') === 'edit';
\$namaKategori = \(item['nama_kategori'] ?? (\)old['nama_kategori'] ?? '');
?>

<h2><?= \$isEdit ? 'Edit Kategori' : 'Tambah Kategori Baru' ?></h2>

<?php if (!empty(\$errors)): ?>
    <div style="color: red; margin-bottom: 15px;">
        <ul>
            <?php foreach (errors as err): ?>
                <li><?= Security::e(is_array(\$err) ? implode(', ', err) : err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">

    <div>
        <label>Nama Kategori:</label><br>
        <input type="text" name="nama_kategori" value="<?= Security::e(\$namaKategori) ?>" required>
    </div><br>

    <button type="submit"><?= \$isEdit ? 'Perbarui' : 'Simpan' ?></button>
    <a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/kategori">Batal</a>
</form>
```

### Langkah 6: Tambahkan Menu Navigasi Admin
Buka `app/Views/Admin/layouts/header.php` dan tambahkan tautan menu ke modul baru:

```php
<a href="<?= rtrim(Env::get('APP_URL'), '/') ?>/admin/kategori">Kategori</a> |
```

---

## 3. Skenario B: Menambah Modul di Area Public (Contoh: `Blog`)

Jika Anda ingin membuat halaman baca artikel untuk pengunjung umum dengan rute `/blog` dan `/blog/detail/{id}`:

1. **Buat `app/Modules/Public/Blog/BlogModule.php`:**
   ```php
   <?php
   class BlogModule extends CoreModule
   {
       protected \$endpoint = '/posts';
       protected \$authRules = [
           'index'  => false, // Akses publik tanpa token JWT
           'detail' => false
       ];
   }
   ```
2. **Buat `app/Modules/Public/Blog/BlogController.php`:**
   ```php
   <?php
   class BlogController extends CoreController
   {
       protected \$area = 'Public';
       protected \$viewPath = 'blog';

       public function __construct()
       {
           \$this->module = new BlogModule();
       }

       // Method index() otomatis diwarisi dari CoreController
       
       public function detail(\$id)
       {
           \$res = this->module->getById((int) id);
           
           if (\(res['status_code'] === 404 \vert{}\vert{} empty(\)res['body']['data'])) {
               http_response_code(404);
               die('Artikel tidak ditemukan.');
           }

           this->view("this->viewPath}/detail", [
               'post' => \$res['body']['data']
           ]);
       }
   }
   ```
3. **Buat File Tampilan di `app/Views/Public/blog/`:**
   * `index.php` untuk menampilkan daftar artikel (gunakan `Security::e()` pada judul).
   * `detail.php` untuk menampilkan isi lengkap artikel (tampilkan isi artikel menggunakan `<?= Security::cleanHtml($post['konten'] ?? '') ?>` agar format HTML dari *Rich Text Editor* tetap terjaga dengan aman).

---

## 4. Checklist Keamanan & Integrasi API

Sebelum menguji modul baru di browser, pastikan poin-poin berikut sudah terpenuhi:

- [ ] **Kesesuaian `$fillable` API:** Kunci array yang dikembalikan oleh `mapPayload()` sama persis dengan kolom yang diizinkan oleh REST API agar tidak memicu status `400 Bad Request`.
- [ ] **Type-Casting Numerik:** Seluruh nilai bertipe angka (seperti `id`, `harga`, `kategori_id`) dikonversi menggunakan `(int)` di dalam `mapPayload()`.
- [ ] **Token CSRF:** Setiap form dengan metode `POST` (termasuk tombol hapus data) wajib menyertakan `<input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">`.
- [ ] **Output Escaping:** Seluruh variabel teks biasa di file `Views/` dibungkus menggunakan `Security::e($variabel)`.
- [ ] **Rich Text Content:** Input dari editor teks panjang disaring menggunakan `Security::cleanHtml()` di dalam `mapPayload()`, bukan `Security::cleanInput()`.
