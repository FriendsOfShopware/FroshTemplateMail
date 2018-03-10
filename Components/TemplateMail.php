<?php

namespace FroshTemplateMail\Components;

use Shopware\Components\Theme\Inheritance;
use Shopware\Models\Mail\Mail;

/**
 * Class TemplateMail
 * @package FroshTemplateMail\Components
 * @author Soner Sayakci <shyim@posteo.de>
 */
class TemplateMail extends \Shopware_Components_TemplateMail
{
    /**
     * @var Inheritance
     */
    private $themeInheritance;

    /**
     * TemplateMail constructor.
     * @param Inheritance $inheritance
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function __construct(Inheritance $inheritance)
    {
        $this->themeInheritance = $inheritance;
    }

    /**
     * @param \Shopware\Models\Mail\Mail|string $mailModel
     * @param array $context
     * @param null $shop
     * @param array $overrideConfig
     * @return \Enlight_Components_Mail
     * @author Soner Sayakci <shyim@posteo.de>
     * @throws \Enlight_Exception
     * @throws \Exception
     */
    public function createMail($mailModel, $context = [], $shop = null, $overrideConfig = [])
    {
        if (!is_object($mailModel)) {
            $modelName = $mailModel;
            $mailModel = $this->getModelManager()->getRepository(Mail::class)->findOneBy(['name' => $mailModel]);

            if (!$mailModel) {
                throw new \Enlight_Exception("Mail-Template with name '{$modelName}' could not be found.");
            }
        }

        if (null !== $shop) {
            $this->setShop($shop);
        }

        if ($this->shop) {
            $this->updateMail($mailModel);
        }

        return parent::createMail($mailModel, $context, $shop, $overrideConfig);
    }

    /**
     * @param Mail $mailModel
     * @author Soner Sayakci <shyim@posteo.de>
     * @throws \Enlight_Event_Exception
     * @throws \Exception
     */
    private function updateMail(Mail $mailModel)
    {
        $this->updateTemplateDirs();

        $htmlFile = sprintf('email/%s.html.tpl', $mailModel->getName());
        $textFile = sprintf('email/%s.text.tpl', $mailModel->getName());
        $subjectFile = sprintf('email/%s.subject.tpl', $mailModel->getName());

        $customMailTemplate = false;

        if ($this->getTemplate()->templateExists($htmlFile)) {
            $mailModel->setIsHtml(true);
            $mailModel->setContentHtml(sprintf('{include file="%s"}', $htmlFile));
            $customMailTemplate = true;
        }

        if ($this->getTemplate()->templateExists($textFile)) {
            $mailModel->setContent(sprintf('{include file="%s"}', $textFile));
            $customMailTemplate = true;
        }

        if ($this->getTemplate()->templateExists($subjectFile)) {
            $mailModel->setSubject(sprintf('{include file="%s"}', $subjectFile));
            $customMailTemplate = true;
        }

        if ($customMailTemplate) {
            $this->getTranslationReader()->delete(null, 'config_mails', $mailModel->getId());
        }
    }

    /**
     * @throws \Enlight_Event_Exception
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function updateTemplateDirs()
    {
        $templateDirs = $this->themeInheritance->getTemplateDirectories($this->getShop()->getTemplate());
        $this->getTemplate()->setTemplateDir($templateDirs);
    }

    /**
     * @return \Enlight_Template_Manager
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function getTemplate()
    {
        return $this->getStringCompiler()->getView();
    }
}