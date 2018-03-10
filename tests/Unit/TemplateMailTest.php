<?php

use Doctrine\ORM\EntityRepository;
use FroshTemplateMail\Components\TemplateMail;
use PHPUnit\Framework\TestCase;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Theme\Inheritance;
use Shopware\Models\Mail\Mail;
use Shopware\Models\Shop\Shop;
use Shopware\Models\Shop\Template;

/**
 * Class TemplateMailTest
 * @author Soner Sayakci <shyim@posteo.de>
 */
class TemplateMailTest extends TestCase
{
    /**
     * @var TemplateMail
     */
    private $templateMail;

    /**
     * @author Soner Sayakci <shyim@posteo.de>
     */
    protected function setUp()
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->any())
            ->method('findOneBy')
            ->willReturn(null);

        $manager = $this->createMock(ModelManager::class);
        $manager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);

        $stringCompiler = new Shopware_Components_StringCompiler(Shopware()->Template());

        $this->templateMail = new TemplateMail($this->createThemeInheritance());
        $this->templateMail->setShop($this->createShopMock(1, $this->createTemplateMock()));
        $this->templateMail->setModelManager($manager);
        $this->templateMail->setStringCompiler($stringCompiler);
    }

    /**
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function testNormalMailTemplate()
    {
        $mail = new Mail();
        $mail->setName('sOrder');
        $mail->setIsHtml(true);
        $mail->setContentHtml('html');
        $mail->setContent('text');


        $this->templateMail->createMail($mail, [
            'sShopUrl' => 'lol',
            'sShopName' => 'lol'
        ]);

        $this->assertEquals('html', $mail->getContentHtml());
        $this->assertEquals('text', $mail->getContent());
        $this->assertEquals(true, $mail->isHtml());
    }

    /**
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function testThemeMailTemplate()
    {
        $mail = new Mail();
        $mail->setName('sBIRTHDAY');
        $mail->setIsHtml(true);
        $mail->setContentHtml('html');
        $mail->setContent('text');


        $sendMailObject = $this->templateMail->createMail($mail, [
            'sShopUrl' => 'lol',
            'sShopName' => 'lol'
        ]);

        $this->assertEquals('{include file="email/sBIRTHDAY.html.tpl"}', $mail->getContentHtml());
        $this->assertEquals('text', $mail->getContent());
        $this->assertEquals(true, $mail->isHtml());

        $this->assertEquals('HAYY', $sendMailObject->getBodyHtml(true));
    }

    /**
     * @throws Enlight_Exception
     * @throws Exception
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function testThemeMailTemplateWithVariables()
    {
        $mail = new Mail();
        $mail->setName('variables');
        $mail->setIsHtml(true);
        $mail->setContentHtml('html');
        $mail->setContent('text');

        $testString = uniqid('shopware', true);

        $sendMailObject = $this->templateMail->createMail($mail, [
            'sShopUrl' => 'lol',
            'sShopName' => 'lol',
            'sVariableTest' => $testString
        ]);

        $this->assertEquals('{include file="email/variables.html.tpl"}', $mail->getContentHtml());
        $this->assertEquals('text', $mail->getContent());
        $this->assertEquals(true, $mail->isHtml());

        $this->assertEquals($testString, $sendMailObject->getBodyHtml(true));
    }

    /**
     * @throws Enlight_Exception
     * @throws Exception
     * @author Soner Sayakci <shyim@posteo.de>
     */
    public function testThemeMailTemplateAllTemplates()
    {
        $mail = new Mail();
        $mail->setName('all_files_should_be_exist');
        $mail->setIsHtml(true);
        $mail->setContentHtml('html');
        $mail->setContent('text');

        $sendMailObject = $this->templateMail->createMail($mail, [
            'sShopUrl' => 'lol',
            'sShopName' => 'lol'
        ]);

        $this->assertEquals('{include file="email/all_files_should_be_exist.html.tpl"}', $mail->getContentHtml());
        $this->assertEquals('{include file="email/all_files_should_be_exist.text.tpl"}', $mail->getContent());
        $this->assertEquals('{include file="email/all_files_should_be_exist.subject.tpl"}', $mail->getSubject());
        $this->assertEquals(true, $mail->isHtml());

        $this->assertEquals('TPL-HTML', $sendMailObject->getBodyHtml(true));
    }

    /**
     * @param $shopId
     * @param $templateStub
     * @return PHPUnit_Framework_MockObject_MockObject
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function createShopMock($shopId, $templateStub)
    {
        return $this->createConfiguredMock(Shop::class, [
            'getMain' => null,
            'getid' => $shopId,
            'getTemplate' => $templateStub,
        ]);
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function createTemplateMock()
    {
        return $this->createConfiguredMock(Template::class, ['getId' => 4]);
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     * @author Soner Sayakci <shyim@posteo.de>
     */
    private function createThemeInheritance()
    {
        $inheritance = $this->createMock(Inheritance::class);
        $inheritance->method('getTemplateDirectories')
            ->willReturn(dirname(__DIR__) . '/views/');

        return $inheritance;
    }
}