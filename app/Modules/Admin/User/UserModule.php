<?php
class UserModule extends CoreModule
{
    protected $endpoint = '/users';
    protected $authRules = [
        'index'  => true,
        'detail' => true,
        'store'  => true,
        'update' => true,
        'delete' => true
    ];
}
