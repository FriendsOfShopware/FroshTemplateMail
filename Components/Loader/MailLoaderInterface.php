<?php

namespace FroshTemplateMail\Components\Loader;

use Shopware\Models\Mail\Mail;

/**
 * Interface MailLoaderInterface
 *
 * @author Soner Sayakci <shyim@posteo.de>
 */
interface MailLoaderInterface
{
    /**
     * This method returns extensions which can be handled by the loader
     *
     * @return array
     *
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function canHandleExtensions(): array;

    /**
     * @param Mail   $mail
     * @param string $templatePath
     * @param string $resolvedTemplatePath
     *
     * @return string
     *
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function loadMail(Mail $mail, string $templatePath, string $resolvedTemplatePath): string;
}
