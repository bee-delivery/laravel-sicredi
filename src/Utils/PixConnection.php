<?php

namespace Beedelivery\Sicredi\Utils;

use Carbon\Carbon;
use Beedelivery\Sicredi\Utils\Connection;
use GuzzleHttp\Client;

class PixConnection extends Connection
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $path;
    protected $accessToken;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->baseUrl  = config('sicredi-pix.base_url');
        $this->username = config('sicredi-pix.basic_user');
        $this->password = config('sicredi-pix.basic_password');
        $this->path     = config('sicredi-pix.certificate_path');

        $this->getAccessToken();
    }

/*
    Recupera o Access Token caso exista ou gera um novo
*/
    public function getAccessToken()
    {  

        if (isset($_SESSION["sicrediTokenPix"])) {
            $token = $_SESSION["sicrediTokenPix"];
            
            $diffInSeconds = Carbon::parse($token['created_at'])->diffInSeconds(now());

            if ($diffInSeconds <= $token['expires_in']) {
                $this->accessToken = $token['token_type'].' '.$token['access_token'];
                return $this->accessToken;
            }
        }

        $params = [
            'url'       => $this->baseUrl,
            'username'  => $this->username,
            'password'  => $this->password,
            'path'      => $this->path,
        ];

        $response = $this->auth($params);

        if($response['code'] == 200){
            $token['token_type'] = $response['response']['token_type'];
            $token['access_token'] = $response['response']['access_token'];
            $token['expires_in'] = $response['response']['expires_in'];
            $token['scope'] = $response['response']['scope'];
            $token['created_at'] = now();

            $_SESSION["sicrediTokenPix"] = $token;
        }
        
        $this->accessToken = $token['token_type'].' '.$token['access_token'];
        return $this->accessToken;
    }



    public function auth($params)
    {
        try {

            $certificado = base_path($this->path);
            $client = new Client(['verify' => fopen($certificado, 'r'),]);

            $headerAuth = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic '.base64_encode($params['username'].':'.$params['password'])
            ];

            $dataAuth =  [
                'grant_type' => 'client_credentials',
                'scope' => 'custom.pix.write'
            ];
        
            $response = $client->request('POST', $params['url'] . 'oauth/token', [
                'headers'     => $headerAuth,
                'form_params' => $dataAuth,
            ]);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
            ];
        } catch (RequestException $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}