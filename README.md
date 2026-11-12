# CMS Front-End Framework (PHP MVC)

**CMS Front-End Framework** adalah aplikasi antarmuka *Content Management System* ringan berbasis arsitektur **PHP MVC (Modular)** tanpa ketergantungan *library* pihak ketiga (*Zero External Dependencies*). Aplikasi ini berperan sebagai lapisan klien yang mengonsumsi ** REST API** menggunakan pertukaran data **JSON** dan otentikasi **JSON Web Token (JWT)**.

---

## 1. Fitur Utama

* **Dual-Area Modular Architecture:** Pemisahan tegas antara area pengunjung (`Public`) dan area pengelola konten (`Admin`) pada folder `Modules/` dan `Views/` dengan komponen inti (`Core/`) yang dipakai bersama.
* **Centralized CRUD & Layout System:** Kelas induk `CoreController` dan `CoreModule` menyediakan operasi standar (`index`, `create`, `edit`, `delete`) serta pembungkus antarmuka otomatis (`header.php` & `footer.php`) sesuai area masing-masing.
* **JWT Server-Side Session:** Token JWT disimpan di dalam `$_SESSION['jwt_token']` dengan flag keamanan ketat (`HttpOnly` dan `SameSite=Strict`) agar terlindungi dari pencurian via XSS.
* **REST API & File Upload Client:** `ApiClient` mendukung permintaan `application/json` maupun unggahan berkas `multipart/form-data` menggunakan `CURLFile`.
* **Defense-in-Depth Security:** Dilengkapi sanitasi teks biasa (`Security::cleanInput`), sanitasi konten *Rich Text Editor* (`Security::cleanHtml`), output escaping (`Security::e`), proteksi CSRF (`hash_equals`), serta *Rate Limiter*.

---

## 2. Persyaratan Sistem & Instalasi

### Persyaratan Sistem
* PHP >= 8.0
* Ekstensi PHP `curl`, `json`, dan `fileinfo` aktif
* Web Server Apache (dengan `mod_rewrite` dan `AllowOverride All`) atau Nginx

### Langkah Instalasi
1. **Salin Proyek:** Tempatkan folder proyek ke direktori web server Anda (misalnya `D:/server/xampp/htdocs/cmsframework`).
2. **Konfigurasi Environment:** Buat atau sesuaikan file `.env` di direktori utama (`ROOT_PATH/.env`):
   ```env
   APP_NAME=" CMS"
   APP_URL="http://cmsframework.local"
   API_BASE_URL="http://cmsframework.local/api"
   RATE_LIMIT_MAX=60
   RATE_LIMIT_WINDOW=60
   ```
3. **Konfigurasi Document Root:** Arahkan *Document Root* web server ke folder `public/`. Pastikan file `public/.htaccess` tersedia untuk meneruskan seluruh rute ke `public/index.php`:
   ```apache
   Options -Indexes
   RewriteEngine On

   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
   ```

---

## 3. Struktur Direktori

```text
cmsframework/
├── app/
│   ├── Core/                        # Komponen inti framework
│   │   ├── ApiClient.php            # Klien cURL (JSON & Multipart Upload)
│   │   ├── App.php                  # Front-Controller & Router Public/Admin
│   │   ├── CoreController.php       # Controller induk & CRUD terpusat
│   │   ├── CoreModule.php           # Penghubung endpoint REST API
│   │   ├── Env.php                  # Pembaca variabel lingkungan (.env)
│   │   ├── RateLimiter.php          # Pembatas kuota request per IP
│   │   └── Security.php             # Sanitasi input/HTML, XSS, dan CSRF
│   ├── Modules/
│   │   ├── Public/                  # Modul area pengunjung
│   │   │   └── Home/
│   │   │       └── HomeController.php
│   │   └── Admin/                   # Modul area dasbor pengelola CMS
│   │       ├── Auth/
│   │       │   ├── AuthController.php
│   │       │   └── AuthModule.php
│   │       ├── Dashboard/
│   │       │   └── DashboardController.php
│   │       ├── Post/
│   │       │   ├── PostController.php
│   │       │   └── PostModule.php
│   │       └── User/
│   │           ├── UserController.php
│   │           └── UserModule.php
│   ├── Views/
│   │   ├── Public/                  # Antarmuka halaman pengunjung
│   │   │   ├── layouts/
│   │   │   │   ├── header.php
│   │   │   │   └── footer.php
│   │   │   └── home/
│   │   │       └── index.php
│   │   └── Admin/                   # Antarmuka dasbor pengelola CMS
│   │       ├── layouts/
│   │       │   ├── header.php
│   │       │   └── footer.php
│   │       ├── auth/
│   │       │   └── login.php
│   │       ├── dashboard/
│   │       │   └── index.php
│   │       ├── post/
│   │       │   ├── index.php
│   │       │   └── form.php
│   │       └── user/
│   │           ├── index.php
│   │           └── form.php
│   └── init.php                     # Bootstrapper, Konstanta & Autoloader
├── public/
│   ├── .htaccess                    # URL Rewriting ke index.php
│   └── index.php                    # Entry point publik tunggal
└── .env                             # Variabel lingkungan aplikasi
```

---

## 4. Siklus Hidup Permintaan (Request Lifecycle)

1. **Entry Point (`public/index.php`):** Seluruh permintaan HTTP masuk melalui `.htaccess` ke `public/index.php` yang memuat `app/init.php` dan menginstansiasi kelas `App`.
2. **Inisialisasi (`app/init.php`):**
   * Mendefinisikan konstanta `ROOT_PATH` dan `APP_PATH`.
   * Memulai sesi PHP dengan `cookie_httponly => true` dan `cookie_samesite => 'Strict'`.
   * Memuat konfigurasi `.env` melalui `Env::load()`.
   * Mendaftarkan *autoloader* otomatis untuk memindai `app/Core/`, `app/Modules/Public/*`, dan `app/Modules/Admin/*`.
   * Memeriksa kuota permintaan melalui `RateLimiter::check()` (bawaan: 60 permintaan per 60 detik).
3. **Routing Area (`app/Core/App.php`):**
   * Jika segmen URL pertama adalah `admin`, sistem mengatur `$area = 'Admin'` dengan controller bawaan `DashboardController`.
   * Jika bukan `admin`, sistem mengatur `$area = 'Public'` dengan controller bawaan `HomeController`.
   * Segmen URL berikutnya dipetakan ke `{Modul}Controller`, `{method}`, dan `{params}`.
4. **Controller & View (`app/Core/CoreController.php`):**
   * Memvalidasi token sesi melalui `requireAuth()` untuk rute di area `Admin`.
   * Memuat tampilan dari `app/Views/{$area}/` dan membungkusnya dengan `app/Views/{$area}/layouts/header.php` dan `footer.php`.
5. **Komunikasi API (`app/Core/ApiClient.php`):**
   * Mengirim permintaan HTTP via `cURL` ke `API_BASE_URL` dengan header `Accept: application/json` dan `Authorization: Bearer <jwt_token>`.

---

## 5. Spesifikasi Komunikasi & Pemetaan Endpoint API

### Standar Header HTTP (`ApiClient.php`)

| Header | Nilai | Keterangan |
| :--- | :--- | :--- |
| `Accept` | `application/json` | Dikirim pada setiap permintaan untuk memastikan respons JSON. |
| `Content-Type` | `application/json` | Otomatis ditambahkan pada metode `POST` dan `PUT` (kecuali saat *multipart upload*). |
| `Authorization` | `Bearer <jwt_token>` | Diambil dari `$_SESSION['jwt_token']` jika parameter `$requireAuth = true`. |

### Tabel Pemetaan Route CMS ke Endpoint API

#### A. Modul Otentikasi & Dasbor (`Admin`)

| Route Front-End | Controller & Method | Endpoint API | Metode HTTP | Auth JWT | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `/admin` | `DashboardController::index()` | - | `GET` | **Wajib** | Halaman utama dasbor pengelola |
| `/admin/auth/login` | `AuthController::login()` | `/auth/login` | `POST` | Publik | Autentikasi dan simpan token ke sesi |
| `/admin/auth/logout` | `AuthController::logout()` | *(Lokal Sesi)* | `GET` | - | Menghapus sesi lokal & redirect login |

#### B. Modul Konten Artikel (`Post`)

| Route Front-End | Controller & Method | Endpoint API | Metode HTTP | Auth JWT | Payload JSON (`$fillable`) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `/` | `HomeController::index()` | `/posts` | `GET` | Publik | Menampilkan daftar artikel publik |
| `/admin/post` | `PostController::index()` | `/posts` | `GET` | **Wajib** | Menampilkan daftar artikel di dasbor |
| `/admin/post/create` | `PostController::create()` | `/posts/store` | `POST` | **Wajib** | `judul`, `slug`, `konten`, `kategori_id`, `status` |
| `/admin/post/edit/{id}` | `PostController::edit($id)` | `/posts/ubah/{id}` | `PUT` | **Wajib** | `judul`, `slug`, `konten`, `kategori_id`, `status` |
| `/admin/post/delete/{id}` | `PostController::delete($id)` | `/posts/hapus/{id}` | `DELETE` | **Wajib** | - |

#### C. Modul Manajemen Pengguna (`User`)

| Route Front-End | Controller & Method | Endpoint API | Metode HTTP | Auth JWT | Payload JSON (`$fillable`) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `/admin/user` | `UserController::index()` | `/users` | `GET` | **Wajib** | Menampilkan daftar pengguna |
| `/admin/user/create` | `UserController::create()` | `/users/store` | `POST` | **Wajib** | `username`, `email`, `password`, `role` |
| `/admin/user/edit/{id}` | `UserController::edit($id)` | `/users/ubah/{id}` | `PUT` | **Wajib** | `username`, `email`, `password` *(opsional)*, `role` |
| `/admin/user/delete/{id}` | `UserController::delete($id)` | `/users/hapus/{id}` | `DELETE` | **Wajib** | - |

### Penanganan Kode Status HTTP

| Kode Status | Arti Menurut API | Tindakan pada Front-End |
| :--- | :--- | :--- |
| **`200 OK`** | Permintaan berhasil diproses. | Simpan token (login) atau redirect ke halaman daftar data. |
| **`201 Created`** | Data baru berhasil dibuat. | Redirect pengguna ke halaman indeks modul terkait. |
| **`400 Bad Request`** | Validasi gagal atau kolom di luar `$fillable`. | Render ulang form beserta pesan error pada `body['errors']`. |
| **`401 Unauthorized`** | Token JWT kosong, tidak valid, atau kedaluwarsa. | `ApiClient` menghapus `$_SESSION['jwt_token']` dan mengarahkan ke `/admin/auth/login`. |
