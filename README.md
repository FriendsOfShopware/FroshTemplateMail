# Store mail templates in theme (WIP)

This plugin allows to store the mails in theme instead of database. This gives us advantages like

* easier deployment
* translate it using snippets
* build your mail template using includes / extends / blocks / inheritance

## Requirements

* Shopware 5.4.x or higher
* PHP 7.0

## Template location

Example Mail **sORDER**

* HTML Template
  * themes/Frontend/MyTheme/email/sORDER.html.tpl
* Text Template
  * themes/Frontend/MyTheme/email/sORDER.text.tpl
* Subject Template
  * themes/Frontend/MyTheme/email/sORDER.subject.tpl
  
  
## Write your own loader

You can also implement your custom loader. Create a new class and implement the interface `FroshTemplateMail/Components/Loader/MailLoaderInterface` and register it using tag `frosh_template_mail.loader`.

**Exmaple**

```php
<?php
namespace MyPlugin;

use FroshTemplateMail\Components\Loader\MailLoaderInterface;
use Shopware\Models\Mail\Mail;

class TwigMailLoader implements MailLoaderInterface {
    
    private $twig;
    
    public function __construct(\Twig_Enviroment $twig)
    {
        $this->twig = $twig;
    }

    public function canHandleExtensions(): array
    {
        return ['twig'];
    }
    
    public function loadMail(Mail $mail, string $templatePath, string $resolvedTemplatePath): string
    {
        return $this->twig->render($resolvedTemplatePath);
    }
}
```

```xml
<service id="my_plugin.twig_loader" class="MyPlugin\TwigMailLoader">
    <argument type="service" id="twig"/>
    <tag name="frosh_template_mail.loader"/>
</service>
```