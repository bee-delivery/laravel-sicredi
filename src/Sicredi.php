<?php

namespace Beedelivery\Iza;

use sicredi\Banking;
use sicredi\Cobranca;

class Sicredi
{
    /*
     * Retorna uma nova instância de Banking.
     *
     * @return \Sicredi\Banking
     */
    public function banking()
    {
        return new Banking();
    }

    /*
     * Retorna uma nova instância de Cobranca.
     *
     * @return \Sicredi\Cobranca
     */
    public function pix()
    {
        return new Cobranca();
    }
}