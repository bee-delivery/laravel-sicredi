<?php

namespace Beedelivery\Sicredi;

class Sicredi
{
    public function cobranca()
    {
        return new Cobranca();
    }

    public function pix($alias = null)
    {
        return new Pix($alias);
    }
}