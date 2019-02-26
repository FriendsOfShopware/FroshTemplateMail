With Template Mail you can swap out your mail templates in the backend to your theme. So your mail templates are versioned, can inherit from each other and all the other great things you can do in Smarty.
The plugin also supports loaders. So other plugins can add file extensions where they can create the mail.
As an example for this feature there is the plugin FroshTemplateMjml, which adds support for MJML. So you can build mails according to MJML standard.

### How do I use the plugin?

After the installation nothing changes for you.
You can now start to swap out the first templates in your theme.

Example Paths:

* themes/Frontend/MyTheme/email/sORDER.html.tpl - Inhalt der sOrder in HTML Form<br>
* themes/Frontend/MyTheme/email/sORDER.text.tpl - Inhalt der sOrder in Text Form<br>
* themes/Frontend/MyTheme/email/sORDER.subject.tpl - Titel der sOrder Mail<br>

The translations can either be done via snippets or you can create your own TPL for each shop.

* themes/Frontend/MyTheme/email/sORDER-SHOPID.html.tpl
* themes/Frontend/MyTheme/email/sORDER-SHOPID.text.tpl
* themes/Frontend/MyTheme/email/sORDER-SHOPID.subject.tpl


The plugin is provided by the Github Organization [FriendsOfShopware](https://github.com/FriendsOfShopware/).
Maintainer of the plugin is [Soner Sayakci](https://github.com/shyim).
You can find the Github Repository [here](https://github.com/FriendsOfShopware/FroshTemplateMail)
[For questions / errors please create a Github Issue](https://github.com/FriendsOfShopware/FroshTemplateMail/issues/new)