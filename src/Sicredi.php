<?php

namespace Beedelivery\Sicredi;

class Sicredi
{
    public function cobranca()
    {
        return new Cobranca();
    }

    public function pix()
    {
        return new Pix();
    }
}