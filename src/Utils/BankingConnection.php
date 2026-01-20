<?php

namespace Beedelivery\Sicredi\Utils;

use Carbon\Carbon;
use Beedelivery\Sicredi\Utils\Connection;

class BankingConnection extends Connection
{

    private $alias;
    public function __construct($alias = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->alias = $alias;
        $config = null;

        if ($alias && config('sicredi.accounts.' . $alias)) {
            $config = config('sicredi.accounts.' . $alias);
        } else {
            $config = [
                'basic_user'       => config('sicredi.basic_user'),
                'basic_password'   => config('sicredi.basic_password'),
                'x_api_key'        => config('sicredi.x_api_key'),
                'certificate_path' => config('sicredi.certificate_path'),
                'cooperativa'      => config('sicredi.cooperativa'),
                'posto'            => config('sicredi.posto'),
            ];
        }
        $this->baseUrl  = config('sicredi.base_url');
        $this->apiKey   = $config['x_api_key'];
        $this->username = $config['basic_user'];
        $this->password = $config['basic_password'];
        $this->path     = $config['certificate_path'];
        $this->cooperativa = $config['cooperativa'];
        $this->posto = $config['posto'];
        $this->getAccessToken();
    }

    /*
        Recupera o Access Token caso exista ou gera um novo
    */
    public function getAccessToken()
    {
        $sessionKey = $this->alias ? "sicrediToken_{$this->alias}" : "sicrediToken";
        if (isset($_SESSION[$sessionKey])) {
            $token = $_SESSION[$sessionKey];

            $diffInSeconds = Carbon::parse($token['created_at'])->diffInSeconds(now());

            if ($diffInSeconds <= $token['expires_in']) {
                $this->accessToken = $token['token_type'] . ' ' . $token['access_token'];
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

                if ($response['code'] == 200) {
                    $token['token_type'] = $response['response']['token_type'];
                    $token['access_token'] = $response['response']['access_token'];
                    $token['expires_in'] = $response['response']['expires_in'];
                    $token['refresh_token'] = $response['response']['refresh_token'];
                    $token['refresh_expires_in'] = $response['response']['refresh_expires_in'];
                    $token['scope'] = $response['response']['scope'];
                    $token['created_at'] = now();

                    $_SESSION[$sessionKey] = $token;

                    $this->accessToken = $token['token_type'] . ' ' . $token['access_token'];
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

        if ($response['code'] == 200) {
            $token['token_type'] = $response['response']['token_type'];
            $token['access_token'] = $response['response']['access_token'];
            $token['expires_in'] = $response['response']['expires_in'];
            $token['refresh_token'] = $response['response']['refresh_token'];
            $token['refresh_expires_in'] = $response['response']['refresh_expires_in'];
            $token['scope'] = $response['response']['scope'];
            $token['created_at'] = now();

            $_SESSION[$sessionKey] = $token;
        }

        $this->accessToken = $token['token_type'] . ' ' . $token['access_token'];
        return $this->accessToken;
    }
}
