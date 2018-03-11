<?php


namespace FroshTemplateMail\Components\Loader;
use Shopware\Models\Mail\Mail;

/**
 * Class SmartyLoader
 * @author Soner Sayakci <shyim@posteo.de>
 */
class SmartyLoader implements MailLoaderInterface
{
    /**
     * This method returns extensions which can be handled by the loader
     * @return array
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function canHandleExtensions(): array
    {
        return ['tpl'];
    }

    /**
     * @param Mail $mail
     * @param string $templatePath
     * @param string $resolvedTemplatePath
     * @return string
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function loadMail(Mail $mail, string $templatePath, string $resolvedTemplatePath): string
    {
        return sprintf('{include file="%s"}', $templatePath);
    }
}