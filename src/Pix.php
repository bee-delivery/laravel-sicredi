<?php

namespace Beedelivery\Sicredi;

use Beedelivery\Sicredi\Utils\Helpers;
use Beedelivery\Sicredi\Utils\PixConnection;

class Pix
{
    use Helpers;

    protected $response;

    public function __construct()
    {
        $this->response = new PixConnection();
    }

    //Criar pagamento Pix via Chave
    public function createPayment($params)
    {
        try {
            $conta = [
                'conta' => config('sicredi-pix.conta'),
                'cooperativa' => config('sicredi-pix.cooperativa'),
                'documento' => config('sicredi-pix.documento'),
            ];
            $params = array_merge($params, $conta);
            $this->validateCreatePixParams($params);


            return $this->response->post('multipag/v1/pagamentos/pix/chave', $params);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /**
     * Recupera os detalhes de um pix
     * [GET] /multipag/v1/pagamentos/pix/{idTransacao}
     * @param string $idTransacao
     * @return array
     */
    public function getPayment($idTransacao)
    {
        try {
            $header = [
                'x-cooperativa' => config('sicredi-pix.cooperativa'),
                'x-conta' => config('sicredi-pix.conta'),
                'x-documento' => config('sicredi-pix.documento'),
            ];
            return $this->response->get('multipag/v1/pagamentos/pix/' . $idTransacao, null, $header);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /**
     * Cancela um pagamento agendado de pix
     * [POST] /multipag/v1/pagamentos/pix/cancelamentos
     * @param string $idTransacao
     * @return array
     */
    public function cancelPayment($idTransacao)
    {
        try {
            $params = [
                'idTransacao' => $idTransacao,
                'conta' => config('sicredi-pix.conta'),
                'cooperativa' => config('sicredi-pix.cooperativa'),
                'documento' => config('sicredi-pix.documento'),
            ];

            return $this->response->patch('multipag/v1/pagamentos/pix/cancelamentos', $params, null, null);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}
