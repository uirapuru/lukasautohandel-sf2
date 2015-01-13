<?php
namespace Dende\FrontBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SocialLinksCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $links = $container->getExtension('front')->getSocialLinks();
        $container->setParameter('social_links', $links);
    }
}
