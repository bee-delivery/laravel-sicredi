<?php

return [
    'base_url'         => env('SICREDI_URL', ''),
    'basic_user'       => env('SICREDI_BASIC_USER', ''),
    'basic_password'   => env('SICREDI_BASIC_PASSWORD', ''),
    'x_api_key'        => env('SICREDI_API_KEY', ''),
    'certificate_path' => env('SICREDI_CERTIFICATE', ''),
    'cooperativa'      => env('SICREDI_COOPERATIVA'), '',
    'posto'            => env('SICREDI_POSTO', ''),

    'accounts' => [
        // 'basic_user'       => env('SICREDI_BASIC_USER_ALIAS1'),
        // 'basic_password'   => env('SICREDI_BASIC_PASSWORD_ALIAS1'),
        // 'x_api_key'        => env('SICREDI_API_KEY_ALIAS1'),
        // 'certificate_path' => env('SICREDI_CERTIFICATE_ALIAS1'),
        // 'cooperativa'      => env('SICREDI_COOPERATIVA_ALIAS1'),
        // 'posto'            => env('SICREDI_POSTO_ALIAS1'),
    ]
];
