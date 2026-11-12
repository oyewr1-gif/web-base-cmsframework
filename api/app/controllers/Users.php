<?php
class Users extends Controller {
    // Aturan validasi saat menambah pengguna baru
    protected $validationRules = [
        'username' => ['required', 'min:4'],
        'email'    => ['required', 'email'],
        'password' => ['required', 'min:6']
    ];

    public function __construct() {
        parent::__construct(); // Wajib dipanggil agar proteksi JWT aktif
        $this->modelInstance = $this->model('mUser');

        // Opsional: Batasi akses modul Users hanya untuk role 'admin'
        if (($this->user['role'] ?? '') !== 'admin') {
            $this->response(403, [
                'status'  => 'fail',
                'message' => 'Forbidden: Hanya admin yang diizinkan mengelola data pengguna'
            ]);
        }
    }

    // POST /users/store - Menambah pengguna baru dengan enkripsi password
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->response(405, ['message' => 'Method Not Allowed']);
        }

        $rawInput = json_decode(file_get_contents('php://input'), true) ?? [];
        $input = Validator::sanitize($rawInput);

        // Jalankan validasi
        $validator = new Validator();
        if (!$validator->validate($input, $this->validationRules)) {
            $this->response(400, [
                'status'  => 'fail',
                'message' => 'Validasi gagal',
                'errors'  => $validator->getErrors()
            ]);
        }

        // Cek duplikasi username atau email
        if ($this->modelInstance->findByUsername($input['username'])) {
            $this->response(409, ['status' => 'fail', 'message' => 'Username sudah digunakan']);
        }
        if ($this->modelInstance->findByEmail($input['email'])) {
            $this->response(409, ['status' => 'fail', 'message' => 'Email sudah digunakan']);
        }

        // Enkripsi password sebelum disimpan
        $input['password'] = password_hash($rawInput['password'], PASSWORD_BCRYPT);
        $input['role']     = $input['role'] ?? 'user';

        if ($this->modelInstance->create($input)) {
            $this->response(201, ['status' => 'success', 'message' => 'Pengguna berhasil ditambahkan']);
        } else {
            $this->response(500, ['status' => 'error', 'message' => 'Gagal menyimpan pengguna']);
        }
    }

    // PUT /users/ubah/{id} - Memperbarui data pengguna
    public function ubah($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->response(405, ['message' => 'Method Not Allowed']);
        }

        $existingUser = $this->modelInstance->find($id);
        if (!$id || !$existingUser) {
            $this->response(404, ['message' => 'Pengguna tidak ditemukan']);
        }

        $rawInput = json_decode(file_get_contents('php://input'), true) ?? [];
        $input = Validator::sanitize($rawInput);

        if (empty($input)) {
            $this->response(400, ['message' => 'Tidak ada data yang dikirim untuk diperbarui']);
        }

        // Jika password ikut dikirim dalam request PUT, enkripsi ulang
        if (isset($rawInput['password']) && !empty(trim($rawInput['password']))) {
            if (strlen($rawInput['password']) < 6) {
                $this->response(400, ['status' => 'fail', 'message' => 'Password minimal 6 karakter']);
            }
            $input['password'] = password_hash($rawInput['password'], PASSWORD_BCRYPT);
        } else {
            // Hapus key password agar password lama di database tidak tertimpa nilai kosong
            unset($input['password']);
        }

        if ($this->modelInstance->update($id, $input)) {
            $this->response(200, ['status' => 'success', 'message' => 'Data pengguna berhasil diperbarui']);
        } else {
            $this->response(400, ['status' => 'fail', 'message' => 'Gagal memperbarui data pengguna']);
        }
    }
}
