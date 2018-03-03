<?php

namespace FroshTemplateMail\Components\CompilerPass;

use FroshTemplateMail\Components\TemplateMailFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ReplaceTemplateMailFactoryPass
 * @package FroshTemplateMail\Components\CompilerPass
 * @author Soner Sayakci <shyim@posteo.de>
 */
class ReplaceTemplateMailFactoryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('templatemail_factory')->setClass(TemplateMailFactory::class);
    }
}