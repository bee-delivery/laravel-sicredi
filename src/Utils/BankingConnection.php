<?php

namespace sicredi\Utils;

use sicredi\Utils\Connection;

class BankingConnection extends Connection
{
    protected $baseUrl;
    protected $apiKey;
    protected $username;
    protected $password;
    protected $path;
    protected $refreshToken;

    public function __construct($path, $refresh = null)
    {
        $this->baseUrl  = config('sicredi.base_url');
        $this->apiKey   = config('sicredi.x-api-key');
        $this->username = config('sicredi.basic_user');
        $this->password = config('sicredi.basic_password');
        $this->path = $path;
        $this->refreshToken = $refresh;

        $this->getAccessToken();
    }

/*
    Recupera o Access Token caso exista ou gera um novo
*/
    public function getAccessToken()
    {   
        $params = [
            'url'       => $this->baseUrl,
            'apiKey'    => $this->apiKey,
            'username'  => $this->username,
            'password'  => $this->password,
            'path'      => $this->path,
            'refresh'   => $this->refreshToken,
        ];

        $response = $this->auth($params);
        
    }
}