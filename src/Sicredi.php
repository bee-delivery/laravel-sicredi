<?php

namespace sicredi;

use sicredi\Banking;
use sicredi\Cobranca;

class Sicredi
{
    public function createCobranca($certificatefepath)
    {
        return new CreateCobranca($certificatefepath);
    }
    
    public function cobrancaDetails($certificatefepath)
    {
        return new CobrancaDetails($certificatefepath);
    }
}