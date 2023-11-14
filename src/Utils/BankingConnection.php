<?php

namespace Beedelivery\Sicredi\Utils;

use Carbon\Carbon;
use Beedelivery\Sicredi\Utils\Connection;

class BankingConnection extends Connection
{
    protected $baseUrl;
    protected $apiKey;
    protected $username;
    protected $password;
    protected $path;
    protected $accessToken;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->baseUrl  = config('sicredi.base_url');
        $this->apiKey   = config('sicredi.x_api_key');
        $this->username = config('sicredi.basic_user');
        $this->password = config('sicredi.basic_password');
        $this->path     = config('sicredi.certificate_path');

        $this->getAccessToken();
    }

/*
    Recupera o Access Token caso exista ou gera um novo
*/
    public function getAccessToken()
    {  

        if (isset($_SESSION["sicrediToken"])) {
            $token = $_SESSION["sicrediToken"];
            
            $diffInSeconds = Carbon::parse($token['created_at'])->diffInSeconds(now());

            if ($diffInSeconds <= $token['expires_in']) {
                $this->accessToken = $token['token_type'].' '.$token['access_token'];
                return $this->accessToken;
            }

            if ($diffInSeconds <= $token['refresh_expires_in']) {
                $params = [
                    'url'       => $this->baseUrl,
                    'apiKey'    => $this->apiKey,
                    'username'  => $this->username,
                    'password'  => $this->password,
                    'path'      => $this->path,
                    'refresh'   => $token['refresh_token']
                ];

                $response = $this->auth($params);

                if($response['code'] == 200){
                    $token['id_token'] = $response['response']['id_token'];
                    $token['token_type'] = $response['response']['token_type'];
                    $token['access_token'] = $response['response']['access_token'];
                    $token['expires_in'] = $response['response']['expires_in'];
                    $token['refresh_token'] = $response['response']['refresh_token'];
                    $token['refresh_expires_in'] = $response['response']['refresh_expires_in'];
                    $token['scope'] = $response['response']['scope'];
                    $token['created_at'] = now();

                    $_SESSION["sicrediToken"] = $token;

                    $this->accessToken = $token['token_type'].' '.$token['access_token'];
                    return $this->accessToken;
                }

            }   
        }

        $params = [
            'url'       => $this->baseUrl,
            'apiKey'    => $this->apiKey,
            'username'  => $this->username,
            'password'  => $this->password,
            'path'      => $this->path,
        ];

        $response = $this->auth($params);

        if($response['code'] == 200){
            $token['id_token'] = $response['response']['id_token'];
            $token['token_type'] = $response['response']['token_type'];
            $token['access_token'] = $response['response']['access_token'];
            $token['expires_in'] = $response['response']['expires_in'];
            $token['refresh_token'] = $response['response']['refresh_token'];
            $token['refresh_expires_in'] = $response['response']['refresh_expires_in'];
            $token['scope'] = $response['response']['scope'];
            $token['created_at'] = now();

            $_SESSION["sicrediToken"] = $token;
        }
        
        $this->accessToken = $token['token_type'].' '.$token['access_token'];
        return $this->accessToken;
    }
}