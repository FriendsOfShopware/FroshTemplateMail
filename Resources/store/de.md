Mit Template Mail kannst du deine Mail Templates im Backend in dein Theme auslagern. Somit sind deine Mail Templates versioniert, können von einander erben und all die anderen Tollen Dinge die man in Smarty tun kann.
Das Plugin unterstüzt ebenfalls Loader. So können andere Plugins Dateiendungen hinzufügen, wo sie dann die Mail erstellen können.
Als Beispiel für dieses Feature gibt es das Plugin FroshTemplateMjml, dies fügt Support von MJML ein. So können sie Mails nach MJML Standard bauen.

### Wie benutze ich das Plugin?

Nach der Installation ändert sich erstmal nichts für dich.
Du kannst nun Anfangen die ersten Templates auszulagern in dein Theme.

Beispiel Pfade:

* themes/Frontend/MyTheme/email/sORDER.html.tpl - Inhalt der sOrder in HTML Form<br>
* themes/Frontend/MyTheme/email/sORDER.text.tpl - Inhalt der sOrder in Text Form<br>
* themes/Frontend/MyTheme/email/sORDER.subject.tpl - Titel der sOrder Mail<br>

Die Übersetzungen können entweder via Snippets passieren oder du legst dir pro Shop eine eigene TPL an.

* themes/Frontend/MyTheme/email/sORDER-SHOPID.html.tpl
* themes/Frontend/MyTheme/email/sORDER-SHOPID.text.tpl
* themes/Frontend/MyTheme/email/sORDER-SHOPID.subject.tpl


Das Plugin wird von der Github Organization [FriendsOfShopware](https://github.com/FriendsOfShopware/) entwickelt.
Maintainer des Plugin ist [Soner Sayakci](https://github.com/shyim).
Das Github Repository ist zu finden [hier](https://github.com/FriendsOfShopware/FroshTemplateMail)
[Bei Fragen / Fehlern bitte ein Github Issue erstellen](https://github.com/FriendsOfShopware/FroshTemplateMail/issues/new)