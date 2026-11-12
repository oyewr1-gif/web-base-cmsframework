<?php
abstract class CoreModule {
    protected $endpoint = '';
    protected $authRules = [
        'index' => false,
        'detail' => false,
        'store' => true,
        'update' => true,
        'delete' => true
    ];

    public function getAll() {
        return ApiClient::request('GET', $this->endpoint, [], $this->authRules['index']);
    }

    public function getById($id) {
        return ApiClient::request('GET', "{$this->endpoint}/detail/{$id}", [], $this->authRules['detail']);
    }

    public function store(array $data) {
        return ApiClient::request('POST', "{$this->endpoint}/store", $data, $this->authRules['store']);
    }

    public function update($id, array $data) {
        return ApiClient::request('PUT', "{$this->endpoint}/ubah/{$id}", $data, $this->authRules['update']);
    }

    public function delete($id) {
        return ApiClient::request('DELETE', "{$this->endpoint}/hapus/{$id}", [], $this->authRules['delete']);
    }

    public function uploadMedia(array $files, array $fields = []) {
        return ApiClient::upload("{$this->endpoint}/upload", $files, $fields, true);
    }

}
