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
     *[POST] /v2/pix/consultas
     * Descrição: Pesquisa de pagamentos utilizando uma lista de ids ou de idsRastreio como critério.
     * @param array $params
     * @return array
     */
    public function query($params)
    {
        try {
            $this->validateQueryParams($params);

            return $this->response->post('v2/pix/consultas', $params);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}
