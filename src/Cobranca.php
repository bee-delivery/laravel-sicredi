<?php

namespace Beedelivery\Sicredi;

use Beedelivery\Sicredi\Utils\BankingConnection;
use Beedelivery\Sicredi\Utils\Helpers;

class Cobranca
{
    use Helpers;

    protected $response;

    public function __construct()
    {
        $this->response = new BankingConnection();
    }


    public function details($codigoBeneficiario,  $sicredId)
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

    public function create($params)
    {
        try {
            $this->validateCobrancaParams($params);

            return $this->response->post('cobranca/boleto/v1/boletos', $params);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    public function getBoletoPrint($barCode)
    {
        try {
            $this->validateBarCode($barCode);

            return $this->response->get('cobranca/boleto/v1/boletos/pdf?LinhaDigitavel=' . $barCode);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}
