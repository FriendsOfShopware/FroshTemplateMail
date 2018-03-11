<?php

namespace FroshTemplateMail\Components;

use FroshTemplateMail\Components\Loader\MailLoaderInterface;
use RuntimeException;
use Shopware\Models\Mail\Mail;
use Enlight_Template_Manager as Template;

/**
 * Class TemplateMailLoader
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
     * @author Soner Sayakci <shyim@posteo.de>
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
     * @param Mail $mail
     * @param Template $template
     * @author Soner Sayakci <shyim@posteo.de>
     * @return bool
     */
    public function loadMail(Mail $mail, Template $template) : bool
    {
        $this->template = $template;

        $wasSuccessfull = false;

        if ($subject = $this->load('subject', $mail)) {
            $mail->setSubject($subject);
            $wasSuccessfull = true;
        }

        if ($text = $this->load('text', $mail)) {
            $mail->setContent($text);
            $wasSuccessfull = true;
        }

        if ($html = $this->load('html', $mail)) {
            $mail->setIsHtml(true);
            $mail->setContentHtml($html);
            $wasSuccessfull = true;
        }

        return $wasSuccessfull;
    }

    /**
     * @param string $type
     * @param Mail $mail
     * @return string
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function load(string $type, Mail $mail)
    {
        foreach ($this->extensions as $extension => $loader) {
            $fileName = sprintf('email/%s.%s.%s', $mail->getName(), $type, $extension);
            $filePath = $this->fileExists($fileName);

            if ($filePath) {
                return $loader->loadMail($mail, $fileName, $filePath);
            }
        }

        return false;
    }


    /**
     * @param string $fileName
     * @author Soner Sayakci <shyim@posteo.de>
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