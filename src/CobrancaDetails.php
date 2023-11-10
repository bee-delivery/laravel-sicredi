<?php

namespace sicredi;

use sicredi\Utils\BankingConnection;
use sicredi\Utils\Helpers;

class CobrancaDetails
{
    use Helpers;

    protected $response; 

    public function __construct()
    {
        $this->response = new BankingConnection();
    }


    public function getCobranca($codigoBeneficiario,  $sicredId)
    {
        try {
            
            return $this->response->get("cobranca/boleto/v1/boletos?codigoBeneficiario=$codigoBeneficiario&nossoNumero=$sicredId");

        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}