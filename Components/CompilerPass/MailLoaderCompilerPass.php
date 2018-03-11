<?php

namespace FroshTemplateMail\Components\CompilerPass;

use Shopware\Components\DependencyInjection\Compiler\TagReplaceTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class MailLoaderCompilerPass
 *
 * @author Soner Sayakci <shyim@posteo.de>
 */
class MailLoaderCompilerPass implements CompilerPassInterface
{
    use TagReplaceTrait;

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->replaceArgumentWithTaggedServices($container, 'frosh_template_mail.template_mail_loader', 'frosh_template_mail.loader', 0);
    }
}
