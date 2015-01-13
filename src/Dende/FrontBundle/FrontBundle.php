<?php

namespace Dende\FrontBundle;

use Dende\FrontBundle\DependencyInjection\CompilerPass\SocialLinksCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SocialLinksCompilerPass());
    }
}
