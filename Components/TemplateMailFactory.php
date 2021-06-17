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
            clone $container->get('template')
        );
        $mailer = new TemplateMail(
            $container->get('theme_inheritance'),
            $container->get('frosh_template_mail.template_mail_loader')
        );
        if ($container->initialized('shop')) {
            $mailer->setShop($container->get('shop'));
        }
        $mailer->setModelManager($container->get('models'));
        $mailer->setStringCompiler($stringCompiler);

        return $mailer;
    }
}
