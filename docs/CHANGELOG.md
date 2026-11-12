# Changelog

Seluruh perubahan penting pada pengembangan **CMS Front-End Framework** (transformasi dari *CMS Front-End Framework* dasar) didokumentasikan dalam file ini.

Format penulisan mengacu pada standar [Keep a Changelog](https://keepachangelog.com).

---

## - 2026-11-12

### Ditambahkan (Added)
* **Arsitektur Dual-Area (`Public` & `Admin`):** Pemisahan direktori modul dan antarmuka pengguna menjadi `app/Modules/Public/`, `app/Modules/Admin/`, `app/Views/Public/`, dan `app/Views/Admin/`.
* **Routing Prefix `/admin` (`App.php`):** Deteksi otomatis segmen URL pertama `admin` untuk mengarahkan permintaan ke area `Admin` dengan modul bawaan `DashboardController`.
* **Layout & Proteksi Area Terpusat (`CoreController.php`):** Penambahan properti `$area` untuk memuat kerangka tampilan (`layouts/header.php` & `footer.php`) sesuai konteks area serta pemaksaan otentikasi JWT (`requireAuth()`) pada seluruh operasi di area `Admin`.
* **Sanitasi Rich Text Editor (`Security.php`):** Penambahan metode `Security::cleanHtml()` untuk menyaring konten artikel WYSIWYG dengan mempertahankan tag HTML aman (`<p>`, `<h2>`, `<b>`, `<ul>`, `<img>`, dll.) sekaligus memblokir atribut *event* JavaScript (`onclick`, `onerror`) dan protokol berbahaya (`javascript:`).
* **Dukungan Unggah Berkas (`ApiClient.php`):** Penambahan parameter `$isMultipart` pada `ApiClient::request()` serta metode pembantu `ApiClient::upload()` untuk mengirim berkas gambar/media menggunakan `CURLFile` (`multipart/form-data`).
* **Modul Inti CMS:**
  * `DashboardController` (`/admin`) sebagai halaman utama panel pengelola CMS.
  * `AuthController` & `AuthModule` (`/admin/auth/login` & `/admin/auth/logout`) untuk manajemen sesi token JWT admin.
  * `PostController` & `PostModule` (`/admin/post`) untuk pengelolaan konten artikel beserta generator *slug* otomatis.
  * `UserController` & `UserModule` (`/admin/user`) untuk manajemen akun pengguna CMS sesuai validasi `$fillable` API.
* **Dokumentasi Teknis:** Penambahan dokumentasi sistem (`README.md`) dan panduan standar penambahan modul baru (`MODULE_GUIDE.md`).

### Diubah (Changed)
* **Optimasi Autoloader (`app/init.php`):** Mengganti pemindaian direktori `scandir()` menjadi `glob(..., GLOB_ONLYDIR)` untuk memindai sub-folder `Modules/Public/*` dan `Modules/Admin/*` secara lebih cepat dan hemat memori.
* **Konsistensi Path Global (`app/init.php`):** Merapikan definisi `ROOT_PATH` dan `APP_PATH` untuk mencegah duplikasi `DIRECTORY_SEPARATOR`.
* **Pengamanan Instansiasi Controller (`app/Core/App.php`):** Menambahkan pengecekan `file_exists()` dan `class_exists()` dengan respons HTTP `404` sebelum melakukan instansiasi kelas controller.
* **Rute Redirect Otentikasi (`CoreController::requireAuth`):** Memperbarui tujuan pengalihan pengguna yang belum terotentikasi ke `/admin/auth/login` saat mengakses area `Admin`.

### Diperbaiki (Fixed)
* **URL Rewriting Apache (`public/.htaccess`):** Penambahan aturan `RewriteRule` di dalam folder `public/` untuk mengatasi error *404 Not Found* saat mengakses rute bersih seperti `/admin`.
* **Pemanggilan Metode View (`HomeController.php`):** Penyelarasan pemanggilan tampilan dari `$this->render()` menjadi `$this->view("{$this->viewPath}/index")` agar sesuai dengan kontrak metode induk `CoreController`.

---

## - Rilis Awal (CMS Front-End Framework)

### Fitur Dasar
* Arsitektur dasar PHP MVC Modular (`Core`, `Modules`, `Views`) tanpa *library* pihak ketiga.
* Kelas induk `CoreController` dan `CoreModule` untuk operasi CRUD standar ke REST API.
* Klien HTTP berbasis `cURL` (`ApiClient.php`) untuk pertukaran data `application/json` dan *header* `Authorization: Bearer <jwt_token>`.
* Manajemen sesi aman (`HttpOnly`, `SameSite=Strict`), sanitasi input dasar (`Security::cleanInput`), output escaping (`Security::e`), proteksi CSRF (`Security::csrfToken` & `verifyCsrf`), serta pembatas laju permintaan (`RateLimiter.php`).
