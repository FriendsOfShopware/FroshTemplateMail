<?php

namespace FroshTemplateMail\Components;

use Enlight_Template_Manager as Template;
use FroshTemplateMail\Components\Loader\MailLoaderInterface;
use RuntimeException;
use Shopware\Models\Mail\Mail;
use Shopware\Models\Shop\Shop;

/**
 * Class TemplateMailLoader
 *
 * @author Soner Sayakci <shyim@posteo.de>
 */
class TemplateMailLoader
{
    /**
     * @var MailLoaderInterface[]
     */
    private $extensions = [];

    /**
     * @var Template
     */
    private $template;

    /**
     * TemplateMailLoader constructor.
     *
     * @author Soner Sayakci <shyim@posteo.de>
     *
     * @param MailLoaderInterface[] $loaders
     */
    public function __construct(array $loaders = [])
    {
        foreach ($loaders as $loader) {
            foreach ($loader->canHandleExtensions() as $extension) {
                if (isset($this->extensions[$extension])) {
                    throw new RuntimeException(sprintf('Extension "%s" has been already registered by loader "%s"', $extension, get_class($this->extensions[$extension])));
                }

                $this->extensions[$extension] = $loader;
            }
        }
    }

    /**
     * @param Mail     $mail
     * @param Template $template
     * @param Shop $shop
     *
     * @author Soner Sayakci <shyim@posteo.de>
     *
     * @return bool
     */
    public function loadMail(Mail $mail, Template $template, Shop $shop): bool
    {
        $this->template = $template;

        $wasSuccessfull = false;

        if ($subject = $this->load('subject', $mail, $shop)) {
            $mail->setSubject($subject);
            $wasSuccessfull = true;
        }

        if ($text = $this->load('text', $mail, $shop)) {
            $mail->setContent($text);
            $wasSuccessfull = true;
        }

        if ($html = $this->load('html', $mail, $shop)) {
            $mail->setIsHtml(true);
            $mail->setContentHtml($html);
            $wasSuccessfull = true;
        }

        return $wasSuccessfull;
    }

    /**
     * @param string $type
     * @param Mail   $mail
     * @param Shop   $shop
     *
     * @return string
     *
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function load(string $type, Mail $mail, Shop $shop)
    {
        foreach ($this->extensions as $extension => $loader) {
            $fileNameLanguage = sprintf('email/%s-%d.%s.%s', $mail->getName(), $shop->getId(), $type, $extension);
            $fileNameShop = sprintf('email/%s-%d.%s.%s', $mail->getName(), $shop->getMain() ? $shop->getMain()->getId() : $shop->getId(), $type,  $extension);
            $fileNameFallback = sprintf('email/%s.%s.%s', $mail->getName(), $type, $extension);

            if ($filePath = $this->fileExists($fileNameLanguage)) {
                return $loader->loadMail($mail, $fileNameLanguage, $filePath);
            }

            if ($filePath = $this->fileExists($fileNameShop)) {
                return $loader->loadMail($mail, $fileNameShop, $filePath);
            }

            if ($filePath = $this->fileExists($fileNameFallback)) {
                return $loader->loadMail($mail, $fileNameFallback, $filePath);
            }
        }

        return false;
    }

    /**
     * @param string $fileName
     *
     * @author Soner Sayakci <shyim@posteo.de>
     *
     * @return bool|string
     */
    private function fileExists(string $fileName)
    {
        foreach ($this->template->getTemplateDir() as $dir) {
            if (file_exists($dir . '/' . $fileName)) {
                return $dir . '/' . $fileName;
            }
        }

        return false;
    }
}
