<?php

namespace Magice\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MagiceUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
