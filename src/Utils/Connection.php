<?php

namespace sicredi\Utils;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

Class Connection
{
    public function get() 
    {
        
    }

    public function post($url, $params, $accessToken) 
    {
        try {
            $cliente = new Client();

            $headerCob = [
                'Content-Type'  => 'application/json',
                'Authorization' => 'bearer ' . $accessToken,
                'x-api-key'     => env('SICREDI_API_KEY'),
                'cooperativa'   => '6789', //pegar essas infor do env tbm 
                'posto'         => '03',
            ];   

            $response = $cliente->post(env('SICREDI_URL') . $url, [
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

    public function patch()
    {
        
    }

    public function auth($params)
    {
        try {
            $certificado = base_path($params['path']);    
            $client = new Client(['base_uri'  => $params['baseUrl'],'verify' => fopen($certificado, 'r'),]);
            
            $headerAuth = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-api-key'=> $params['apiKey'],
                'context' => 'COBRANCA',
            ];

            if(!isset($params['refresh'])) {
                $dataAuth =  [
                    'username' => $params['username'],
                    'password' => $params['password'],
                    'scope'=> 'cobranca',
                    'grant_type'=> 'password',
                ];
            }else {
                $dataAuth =  [
                    'scope'=> 'cobranca',
                    'grant_type'=> 'refresh_token',
                    'refresh_token' => $params['refresh'], 
                ];
            }

            $response = $client->request('POST' , 'auth/openapi/token', [
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