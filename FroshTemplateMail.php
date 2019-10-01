<?php

namespace FroshTemplateMail;

use FroshTemplateMail\Components\CompilerPass\MailLoaderCompilerPass;
use FroshTemplateMail\Components\CompilerPass\ReplaceTemplateMailFactoryPass;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FroshTemplateMail
 */
class FroshTemplateMail extends Plugin
{
    /**
     * @param ContainerBuilder $container
     *
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ReplaceTemplateMailFactoryPass());
    }
}
