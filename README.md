# Store Shopware mail templates in theme

[![Join the chat at https://gitter.im/FriendsOfShopware/Lobby](https://badges.gitter.im/FriendsOfShopware/Lobby.svg)](https://gitter.im/FriendsOfShopware/Lobby)
[![Download @ Community Store](https://img.shields.io/badge/endpoint.svg?url=https://api.friendsofshopware.com/FroshTemplateMail)](https://store.shopware.com/frosh46026077660f/froshtemplatemail.html)

This plugin allows to store the mails in theme instead of database. This gives us advantages like

* easier deployment
* translate it using snippets
* build your mail template using includes / extends / blocks / inheritance
* usage of theme configuration


## Requirements

- Shopware 5.4.x or higher
- PHP 7.0


## Installation

- Download latest release
- Extract the zip file in `shopware_folder/custom/plugins/`


## Template location

Create a mail for a specific subshop or language shop (also inheritance in shops works)

Search order in example with sOrder:

* HTML Template
  * themes/Frontend/MyTheme/email/sORDER-SHOPID.html.tpl (Shop ID)
  * themes/Frontend/MyTheme/email/sORDER.html.tpl (Default)
  * Database saved values
* Text Template
  * themes/Frontend/MyTheme/email/sORDER-SHOPID.text.tpl (Shop ID)
  * themes/Frontend/MyTheme/email/sORDER.text.tpl (Default)
  * Database saved values
* Subject Template
  * themes/Frontend/MyTheme/email/sORDER-SHOPID.subject.tpl (Shop ID)
  * themes/Frontend/MyTheme/email/sORDER.subject.tpl (Default)
  * Database saved values

## Loaders

### Available loaders

* [MJML](https://github.com/FriendsOfShopware/FroshTemplateMailMjml)


### Write your own loader

You can also implement your custom loader. Create a new class and implement the interface `FroshTemplateMail/Components/Loader/MailLoaderInterface` and register it using tag `frosh_template_mail.loader`.

**Example**

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


## Contributing

Feel free to fork and send pull requests!


## Licence

This project uses the [MIT License](LICENCE.md).
