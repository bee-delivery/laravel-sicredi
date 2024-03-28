<?php

return [
    'base_url'         => env('SICREDI_URL', ''),
    'client_id'        => env('SICREDI_PIX_CLIENT_ID', ''),
    'client_secret'    => env('SICREDI_PIX_CLIENT_SECRET', ''),
    'certificate_path' => env('SICREDI_PIX_CERTIFICATE', ''),
    'cooperativa'      => env('SICREDI_PIX_COOPERATIVA'), '',
    'conta'            => env('SICREDI_PIX_CONTA', ''),
    'documento'        => env('SICREDI_PIX_DOCUMENTO', ''),
];
