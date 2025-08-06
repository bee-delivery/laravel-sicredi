<?php

namespace Beedelivery\Sicredi\Utils;

use Carbon\Carbon;
use Beedelivery\Sicredi\Utils\Connection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

class PixConnection extends Connection
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $cert;
    protected $certKey;
    protected $certPass;
    protected $accessToken;
    protected $alias;
    protected $cooperativa;
    protected $conta;
    protected $documento;

    public function __construct($alias = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->alias = $alias;
        $config = null;

        if ($alias && config('sicredi-pix.accounts.' . $alias)) {
            $config = config('sicredi-pix.accounts.' . $alias);
        } else {
            // retrocompatibilidade
            $config = [
                'base_url'         => config('sicredi-pix.base_url'),
                'client_id'        => config('sicredi-pix.client_id'),
                'client_secret'    => config('sicredi-pix.client_secret'),
                'certificate_path' => config('sicredi-pix.certificate_path'),
                'cert_key_path'    => config('sicredi-pix.cert_key_path'),
                'cert_key_pass'    => config('sicredi-pix.cert_key_pass'),
                'cooperativa'      => config('sicredi-pix.cooperativa'),
                'conta'            => config('sicredi-pix.conta'),
                'documento'        => config('sicredi-pix.documento'),
            ];
        }

        $this->baseUrl  = $config['base_url'];
        $this->username = $config['client_id'];
        $this->password = $config['client_secret'];
        $this->cert     = $config['certificate_path'];
        $this->certKey  = $config['cert_key_path'];
        $this->certPass = $config['cert_key_pass'];
        $this->cooperativa = $config['cooperativa'] ?? null;
        $this->conta = $config['conta'] ?? null;
        $this->documento = $config['documento'] ?? null;

        $this->getAccessToken();
    }

/*
    Recupera o Access Token caso exista ou gera um novo
*/
    public function getAccessToken()
    {
        $sessionKey = $this->alias ? "sicrediTokenPix_{$this->alias}" : "sicrediTokenPix";

        if (isset($_SESSION[$sessionKey])) {
            $token = $_SESSION[$sessionKey];
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
            $token['access_token'] = $response['response']['access_token'];
            $token['expires_in'] = $response['response']['expires_in'];
            $token['token_type'] = $response['response']['token_type'];
            $token['scope'] = $response['response']['scope'];
            $token['created_at'] = now();

            $_SESSION[$sessionKey] = $token;
        }

        $this->accessToken = $token['token_type'].' '.$token['access_token'];
        return $this->accessToken;
    }

    public function auth($params)
    {
        try {

            $certificado = base_path($this->cert);
            $certificado_key = base_path($this->certKey);
            $client = new Client([
                'verify' => false, // Desativar a verificação SSL, pois estamos fornecendo nossos próprios certificado e chave
                RequestOptions::CERT => $certificado,
                RequestOptions::SSL_KEY => [$certificado_key, $this->certPass],
            ]);

            $headerAuth = [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ];

            $dataAuth =  [
                'grant_type' => 'client_credentials',
                'client_id' => $params['username'],
                'client_secret' => $params['password'],
                'scope' => 'multipag.boleto.pagar multipag.boleto.consultar multipag.tributos.pagar multipag.tributos.consultar multipag.pix.pagar multipag.pix.consultar'
            ];

            $response = $client->request('POST', $params['url'] . 'thirdparty/auth/token', [
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

    public function get($url, $params = null, $header = null)
    {
        try {
            $certificado = base_path($this->cert);
            $certificado_key = base_path($this->certKey);
            $client = new Client([
                'verify' => false, // Desativar a verificação SSL, pois estamos fornecendo nossos próprios certificado e chave
                RequestOptions::CERT => $certificado,
                RequestOptions::SSL_KEY => [$certificado_key, $this->certPass],
            ]);

            $headerPix = [
                'Content-Type'  => 'application/json',
                'Authorization' => $this->accessToken,
                'accept'        => '*/*',
            ];

            if (isset($header)) {
                $headerPix = array_merge($headerPix, $header);
            }

            if (isset($params)) {
                $response = $client->get($this->baseUrl . $url, [
                    'headers'     => $headerPix,
                    'json' => $params,
                ]);
            } else {
                $response = $client->get($this->baseUrl . $url, [
                    'headers'     => $headerPix,
                ]);
            }

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
            ];
        } catch (RequestException $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getResponse()->getBody()->getContents()
            ];
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    public function post($url, $params, $header = null)
    {
        try {

            $certificado = base_path($this->cert);
            $certificado_key = base_path($this->certKey);
            $client = new Client([
                'verify' => false, // Desativar a verificação SSL, pois estamos fornecendo nossos próprios certificado e chave
                RequestOptions::CERT => $certificado,
                RequestOptions::SSL_KEY => [$certificado_key, $this->certPass],
            ]);

            $headerPix = [
                'Content-Type'  => 'application/json',
                'Authorization' => $this->accessToken,
                'accept'        => '*/*',
            ];

            if (isset($header)) {
                $headerPix = array_merge($headerPix, $header);
            }

            $response = $client->post($this->baseUrl . $url, [
                'headers'     => $headerPix,
                'json' => $params,
            ]);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
            ];
        } catch (RequestException $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getResponse()->getBody()->getContents()
            ];
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    public function patch($url, $params = null, $beneficiario, $accessToken)
    {
        try {

            $certificado = base_path($this->cert);
            $certificado_key = base_path($this->certKey);
            $client = new Client([
                'verify' => false, // Desativar a verificação SSL, pois estamos fornecendo nossos próprios certificado e chave
                RequestOptions::CERT => $certificado,
                RequestOptions::SSL_KEY => [$certificado_key, $this->certPass],
            ]);

            $headerPix = [
                'Content-Type'  => 'application/json',
                'Authorization' => $this->accessToken,
                'accept'        => '*/*',
            ];

            if (isset($header)) {
                $headerPix = array_merge($headerPix, $header);
            }

            $response = $client->patch($this->baseUrl . $url, [
                'headers'     => $headerPix,
                'json' => $params,
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
