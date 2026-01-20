<?php

namespace Beedelivery\Sicredi;

class Sicredi
{
    public function cobranca($alias = null)
    {
        return new Cobranca($alias);
    }

    public function pix($alias = null)
    {
        return new Pix($alias);
    }
}