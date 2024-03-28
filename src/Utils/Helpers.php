<?php

namespace Beedelivery\Sicredi\Utils;

use Illuminate\Support\Facades\Validator;

trait Helpers 
{
    /*
     * Valida dados para criacao de cobrancas.
     *
     * @param array $key
     * @return void
     */
    public function validateCobrancaParams($key)
    {
        $validator = Validator::make($key, [
           'beneficiarioFinal.documento'    => 'required|string',  
           'beneficiarioFinal.tipoPessoa'   => 'required|string',
           'beneficiarioFinal.nome'         => 'required|string',
           'pagador.documento'              => 'required|string',
           'pagador.tipoPessoa'             => 'required|string',
           'pagador.nome'                   => 'required|string',
           'pagador.cep'                    => 'nullable|string',
           'pagador.cidade'                 => 'nullable|string',                                 
           'pagador.endereco'               => 'nullable|string',
           'pagador.uf'                     => 'nullable|string',
           'valor'                          => 'required|regex:/^\d+(\.\d{2})?$/',
           'codigoBeneficiario'             => 'required|string',
           'dataVencimento'                 => 'required|date|date_format:Y-m-d',
           'especieDocumento'               => 'required|string',
           'tipoCobranca'                   => 'required|string',
           'seuNumero'                      => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }
   
    public function validateBarCode($barCode)
    {
        $validator = Validator::make($barCode, [
            'barCode' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }
    /*
     * Valida dados para criacao de pagamento pix via chave.
     *
     * @param array $params
     * @return void
     */
    public function validateCreatePixParams($params)
    {
        $validator = Validator::make($params, [
            'conta' => 'required|string',
            'cooperativa' => 'required|string',
            'documento' => 'required|string',
            'chavePix' => 'required|string',
            'documentoBeneficiario' => 'required|string',
            'dataPagamento' => 'required|string',
            'valorPagamento' => 'required|regex:^\d+(\.\d+)?$^',
            'identificadorPagamentoAssociado' => 'required|string',
            'mensagemPix' => 'required|string',
            'idTransacao' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }

    /**
     * Valida se tem ids ou idsRastreio, ao menos um deles é obrigatório.
     *
     * @param array $params
     * @return void
     */
    public function validateQueryParams($params)
    {
        $validator = Validator::make($params, [
            'filtro.ids' => 'nullable|array',
            'filtro.idsRastreio' => 'nullable|array',
            'agenciaOrigem' => 'required|string',
            'contaOrigem' => 'required|string',
            'postoOrigem' => 'required|string',
            'pagina' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }
}