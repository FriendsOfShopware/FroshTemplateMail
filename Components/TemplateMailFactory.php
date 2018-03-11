<?php

namespace FroshTemplateMail\Components;

use Shopware\Components\DependencyInjection\Container;

class TemplateMailFactory
{
    /**
     * @param Container $container
     *
     * @return \Shopware_Components_TemplateMail
     *
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function factory(Container $container)
    {
        $container->load('MailTransport');

        $stringCompiler = new \Shopware_Components_StringCompiler(
            $container->get('Template')
        );
        $mailer = new TemplateMail(
            $container->get('theme_inheritance'),
            $container->get('frosh_template_mail.template_mail_loader')
        );
        if ($container->initialized('Shop')) {
            $mailer->setShop($container->get('Shop'));
        }
        $mailer->setModelManager($container->get('Models'));
        $mailer->setStringCompiler($stringCompiler);

        return $mailer;
    }
}
