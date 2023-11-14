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
           'pagador.cep'                    => 'required|string',
           'pagador.cidade'                 => 'required|string',
           'pagador.documento'              => 'required|string',
           'pagador.nome'                   => 'required|string',
           'pagador.tipoPessoa'             => 'required|string',
           'pagador.endereco'               => 'required|string',
           'pagador.uf'                     => 'required|string',
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
}