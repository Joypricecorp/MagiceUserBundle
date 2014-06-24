<?php

namespace Magice\Bundle\UserBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

class MagiceUserBundle extends Bundle
{
    public function build(ContainerBuilder $builder)
    {
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
