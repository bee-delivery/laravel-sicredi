<?php

namespace Beedelivery\Sicredi;

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