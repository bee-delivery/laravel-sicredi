<?php

namespace Beedelivery\Sicredi;

use Beedelivery\Sicredi\Utils\BankingConnection;
use Beedelivery\Sicredi\Utils\Helpers;

class CreateCobranca
{
    use Helpers;

    protected $response; 

    public function __construct()
    {
        $this->response = new BankingConnection();
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

}