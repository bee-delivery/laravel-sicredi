<?php

namespace Beedelivery\Sicredi\Utils;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class Connection
{
    public function get($url, $params = null)
    {
        try {
            $cliente = new Client();

            $headerCob = [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => $this->accessToken,
                'x-api-key'     => config('sicredi.x_api_key'),
                'cooperativa'   => config('sicredi.cooperativa'), //pegar essas infor do env tbm 
                'posto'         => config('sicredi.posto'),
            ];

            if (isset($params)) {
                $response = $cliente->get(config('sicredi.base_url') . $url, [
                    'headers'     => $headerCob,
                    'json' => $params,
                ]);
            } else {
                $response = $cliente->get(config('sicredi.base_url') . $url, [
                    'headers'     => $headerCob,
                ]);
            }

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

    public function getBinario($url, $params = null)
    {
        try {
            $cliente = new Client();

            $headerCob = [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => $this->accessToken,
                'Accept'        => '*/*',
                'x-api-key'     => config('sicredi.x_api_key'),
                'cooperativa'   => config('sicredi.cooperativa'), //pegar essas infor do env tbm 
                'posto'         => config('sicredi.posto'),
            ];

            if (isset($params)) {
                $response = $cliente->get(config('sicredi.base_url') . $url, [
                    'headers'     => $headerCob,
                    'json' => $params,
                ]);
            } else {
                $response = $cliente->get(config('sicredi.base_url') . $url, [
                    'headers'     => $headerCob,
                ]);
            }

            return [
                'code' => $response->getStatusCode(),
                'response' => $response
            ];
        } catch (RequestException $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    public function post($url, $params)
    {
        try {
            $cliente = new Client();

            $headerCob = [
                'Content-Type'  => 'application/json',
                'Authorization' => $this->accessToken,
                'x-api-key'     => config('sicredi.x_api_key'),
                'cooperativa'   => config('sicredi.cooperativa'),
                'posto'         => config('sicredi.posto'),
            ];

            $response = $cliente->post(config('sicredi.base_url') . $url, [
                'headers'     => $headerCob,
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

    public function patch($url, $params, $beneficiario, $accessToken)
    {
        try {
            $cliente = new Client();

            $headerCob = [
                'Content-Type'  => 'application/json',
                'Authorization' => $accessToken,
                'codigoBeneficiario' => $beneficiario,
                'x-api-key'     => config('sicredi.x_api_key'),
                'cooperativa'   => config('sicredi.cooperativa'),
                'posto'         => config('sicredi.posto'),
            ];

            $response = $cliente->patch(config('sicredi.base_url') . $url, [
                'headers'     => $headerCob,
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

    public function auth($params)
    {
        try {

            $certificado = base_path(config('certificate_path'));
            $client = new Client(['verify' => fopen($certificado, 'r'),]);

            $headerAuth = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-api-key' => $params['apiKey'],
                'context' => 'COBRANCA',
            ];

            if (!isset($params['refresh'])) {
                $dataAuth =  [
                    'username' => $params['username'],
                    'password' => $params['password'],
                    'scope' => 'cobranca',
                    'grant_type' => 'password',
                ];
            } else {
                $dataAuth =  [
                    'scope' => 'cobranca',
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $params['refresh'],
                ];
            }

            $response = $client->request('POST', $params['url'] . 'auth/openapi/token', [
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
