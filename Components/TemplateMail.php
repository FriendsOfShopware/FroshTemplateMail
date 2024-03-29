<?php

namespace FroshTemplateMail\Components;

use Shopware\Components\Theme\Inheritance;
use Shopware\Models\Mail\Mail;

/**
 * Class TemplateMail
 *
 * @author Soner Sayakci <shyim@posteo.de>
 */
class TemplateMail extends \Shopware_Components_TemplateMail
{
    /**
     * @var Inheritance
     */
    private $themeInheritance;

    /**
     * @var TemplateMailLoader
     */
    private $loader;

    /**
     * TemplateMail constructor.
     *
     * @param Inheritance $inheritance
     * @param TemplateMailLoader $loader
     *
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function __construct(Inheritance $inheritance, TemplateMailLoader $loader)
    {
        $this->themeInheritance = $inheritance;
        $this->loader = $loader;
    }

    /**
     * @param \Shopware\Models\Mail\Mail|string $mailModel
     * @param array $context
     * @param null $shop
     * @param array $overrideConfig
     *
     * @return \Enlight_Components_Mail
     *
     * @throws \Exception
     *
     * @throws \Enlight_Exception
     * @author Soner Sayakci <shyim@posteo.de>
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

        if ($shop !== null) {
            $this->setShop($shop);
        }

        if ($this->shop) {
            $this->updateMail($mailModel);

            // Remove when PR https://github.com/shopware/shopware/pull/1520 is merged
            $context['theme'] = $this->themeInheritance->buildConfig($this->getTemplateDir(), $this->getShop(), false);
        }

        return parent::createMail($mailModel, $context, $shop, $overrideConfig);
    }

    /**
     * @param Mail $mailModel
     *
     * @throws \Enlight_Event_Exception
     * @throws \Exception
     * @author Soner Sayakci <shyim@posteo.de>
     *
     */
    private function updateMail(Mail $mailModel)
    {
        $this->updateTemplateDirs();

        if ($this->loader->loadMail($mailModel, $this->getTemplate(), $this->shop)) {
            $this->getTranslationReader()->delete(null, 'config_mails', $mailModel->getId());
        }
    }

    /**
     * @throws \Enlight_Event_Exception
     *
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function updateTemplateDirs()
    {
        $templateDirs = $this->themeInheritance->getTemplateDirectories($this->getTemplateDir());
        $this->getTemplate()->setTemplateDir($templateDirs);
    }

    /**
     * @return \Enlight_Template_Manager
     *
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function getTemplate()
    {
        return $this->getStringCompiler()->getView();
    }

    private function getTemplateDir()
    {
        if ((($theme = $this->getShop()->getTemplate()) === null)
            && $this->getShop()->getMain()
            && ($theme = $this->getShop()->getMain()->getTemplate()) === null) {
            return null;
        }
        return $theme;
    }
}
