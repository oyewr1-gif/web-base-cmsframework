<?php
class AuthModule extends CoreModule
{
    protected $endpoint = '/auth';

    public function login(array $credentials)
    {
        return ApiClient::request('POST', "{$this->endpoint}/login", $credentials, false);
    }
}
