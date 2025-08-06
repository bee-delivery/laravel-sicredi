<?php

return [
    // Configuração retrocompatível (opcional, pode ser removida futuramente)
    'base_url'         => env('SICREDI_PIX_URL', ''),
    'client_id'        => env('SICREDI_PIX_CLIENT_ID', ''),
    'client_secret'    => env('SICREDI_PIX_CLIENT_SECRET', ''),
    'certificate_path' => env('SICREDI_PIX_CERTIFICATE', ''),
    'cert_key_path'    => env('SICREDI_PIX_CERT_KEY', ''),
    'cert_key_pass'    => env('SICREDI_PIX_CERT_PASS', ''),
    'cooperativa'      => env('SICREDI_PIX_COOPERATIVA', ''),
    'conta'            => env('SICREDI_PIX_CONTA', ''),
    'documento'        => env('SICREDI_PIX_DOCUMENTO', ''),

    // Nova configuração para múltiplas contas
    'accounts' => [
        // Exemplo de configuração por alias
        // 'alias1' => [
        //     'base_url'         => env('SICREDI_PIX_URL_ALIAS1', ''),
        //     'client_id'        => env('SICREDI_PIX_CLIENT_ID_ALIAS1', ''),
        //     'client_secret'    => env('SICREDI_PIX_CLIENT_SECRET_ALIAS1', ''),
        //     'certificate_path' => env('SICREDI_PIX_CERTIFICATE_ALIAS1', ''),
        //     'cert_key_path'    => env('SICREDI_PIX_CERT_KEY_ALIAS1', ''),
        //     'cert_key_pass'    => env('SICREDI_PIX_CERT_PASS_ALIAS1', ''),
        //     'cooperativa'      => env('SICREDI_PIX_COOPERATIVA_ALIAS1', ''),
        //     'conta'            => env('SICREDI_PIX_CONTA_ALIAS1', ''),
        //     'documento'        => env('SICREDI_PIX_DOCUMENTO_ALIAS1', ''),
        // ],
        // 'alias2' => [ ... ],
    ],
];
