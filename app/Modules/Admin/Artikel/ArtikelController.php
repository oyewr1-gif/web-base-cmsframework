<?php
class ArtikelController extends CoreController
{
    protected $area = 'Admin';
    protected $viewPath = 'artikel';
    protected $redirectPath = '/admin/artikel';

    public function __construct()
    {
        $this->module = new ArtikelModule();
    }

    protected function mapPayload(array $cleanPost)
    {
        // Gunakan $_POST['konten'] mentah lalu saring dengan cleanHtml() agar tag format tidak hilang
        $rawKonten = $_POST['konten'] ?? '';

        return [
            'judul'       => $cleanPost['judul'] ?? '',
            'slug'        => $this->generateSlug($cleanPost['judul'] ?? ''),
            'konten'      => Security::cleanHtml($rawKonten),
            'kategori_id' => (int) ($cleanPost['kategori_id'] ?? 1),
            'status'      => in_array($cleanPost['status'] ?? '', ['draft', 'publish']) ? $cleanPost['status'] : 'draft'
        ];
    }

    private function generateSlug(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9-]+/', '-', $text);
        return trim($text, '-');
    }
}
