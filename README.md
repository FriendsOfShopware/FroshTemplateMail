# Store mail templates in theme (WIP)

This plugin allows to store the mails in theme instead of database. This gives us advantages like

* easier deployment
* translate it using snippets
* build your mail template using includes / extends / blocks / inheritance

## Template location

Example Mail **sORDER**

* HTML Template
  * themes/Frontend/MyTheme/email/sORDER.html.tpl
* Text Template
  * themes/Frontend/MyTheme/email/sORDER.text.tpl
* Subject Template
  * themes/Frontend/MyTheme/email/sORDER.subject.tpl