<?php

namespace sicredi;

use sicredi\Utils\BankingConnection;
use sicredi\Utils\Helpers;

class Cobranca
{
    use Helpers;

    protected $response; 

    public function __construct($path)
    {
        $this->response = new BankingConnection($path);
    }


    public function createCobranca($params)
    {
        try {
            $this->validateCobrancaParams($params);

            return $this->response->post('cobranca/boleto/v1/boletos',$params);

        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    public function getCobranca($codigoBeneficiario,  $sicredId)
    {
        try {
            
            return $response = $this->response->get("cobranca/boleto/v1/boletos?codigoBeneficiario=$codigoBeneficiario&nossoNumero=$sicredId");

        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}