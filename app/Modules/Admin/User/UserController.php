<?php
class UserController extends CoreController
{
    protected $area = 'Admin';
    protected $viewPath = 'user';
    protected $redirectPath = '/admin/user';

    public function __construct()
    {
        $this->module = new UserModule();
    }

    protected function mapPayload(array $cleanPost)
    {
        $payload = [
            'username' => $cleanPost['username'] ?? '',
            'email'    => $cleanPost['email'] ?? '',
            'role'     => $cleanPost['role'] ?? 'editor' // Sesuaikan jika API memiliki kolom role
        ];

        // Password diambil langsung dari $_POST agar karakter simbol tidak hilang oleh stripslashes
        // Saat edit user, password hanya dikirim jika admin mengisi kolom password baru
        if (!empty($_POST['password'])) {
            $payload['password'] = $_POST['password'];
        }

        return $payload;
    }
}
