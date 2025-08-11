<?php

namespace Beedelivery\Sicredi;

use Beedelivery\Sicredi\Utils\Helpers;
use Beedelivery\Sicredi\Utils\PixConnection;

class Pix
{
    use Helpers;

    protected $response;
    protected $alias;
    protected $contaData;

    public function __construct($alias = null)
    {
        $this->alias = $alias;
        $this->response = new PixConnection($alias);

        // Busca dados da conta do alias ou padrão
        if ($alias && config('sicredi-pix.accounts.' . $alias)) {
            $conf = config('sicredi-pix.accounts.' . $alias);
            $this->contaData = [
                'conta' => $conf['conta'] ?? null,
                'cooperativa' => $conf['cooperativa'] ?? null,
                'documento' => $conf['documento'] ?? null,
            ];
        } else {
            $this->contaData = [
                'conta' => config('sicredi-pix.conta'),
                'cooperativa' => config('sicredi-pix.cooperativa'),
                'documento' => config('sicredi-pix.documento'),
            ];
        }
    }

    //Criar pagamento Pix via Chave
    public function createPayment($params)
    {
        try {
            $params = array_merge($params, $this->contaData);
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
                'x-cooperativa' => $this->contaData['cooperativa'],
                'x-conta' => $this->contaData['conta'],
                'x-documento' => $this->contaData['documento'],
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
                'conta' => $this->contaData['conta'],
                'cooperativa' => $this->contaData['cooperativa'],
                'documento' => $this->contaData['documento'],
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
