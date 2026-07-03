<?php

namespace Beedelivery\Sicredi;

use Beedelivery\Sicredi\Utils\BankingConnection;
use Beedelivery\Sicredi\Utils\Helpers;

class Cobranca
{
    use Helpers;

    protected $response;

    public function __construct($alias = null)
    {
        $this->response = new BankingConnection($alias);
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

    /*
     * Cadastra um contrato Webhook para recebimento de eventos de cobranca.
     *
     * POST /cobranca/boleto/v1/webhook/contrato/
     */
    public function createWebhookContract($params)
    {
        try {
            $this->validateWebhookContractParams($params);

            return $this->response->post('cobranca/boleto/v1/webhook/contrato/', $params);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Consulta o contrato Webhook de um beneficiario.
     *
     * GET /cobranca/boleto/v1/webhook/contratos/
     */
    public function getWebhookContract($cooperativa, $posto, $beneficiario)
    {
        try {
            return $this->response->get("cobranca/boleto/v1/webhook/contratos/?cooperativa=$cooperativa&posto=$posto&beneficiario=$beneficiario");
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
            $this->validateBarCode(['barCode' => $barCode]);

            return $this->response->getBinario('cobranca/boleto/v1/boletos/pdf?LinhaDigitavel=' . $barCode);
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}
