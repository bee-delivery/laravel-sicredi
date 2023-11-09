<?php

namespace sicredi;

use sicredi\Banking;
use sicredi\Cobranca;

class Sicredi
{
    public function createCobranca()
    {
        return new CreateCobranca();
    }
    
    public function cobrancaDetails()
    {
        return new CobrancaDetails();
    }
}