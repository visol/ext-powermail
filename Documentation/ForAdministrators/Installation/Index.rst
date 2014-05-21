.. include:: Images.txt
.. include:: ../../Includes.txt

Installation
^^^^^^^^^^^^


Import
""""""

Import Extension from the TYPO3 Extension Repository to your server.


Install
"""""""

Install the extension and follow the instructions (adding tables,
etc...).

|img-82|

Extension Manager Settings
""""""""""""""""""""""""""

Main configuration for powermail for CMS wide settings.

|img-83|



**Disable IP logging**

If you generally don't want to save the sender IP address in the
database, you can use this checkbox.


**Disable BE Module**

You can disable the backend module if you don't store mails in your
database or if you don't need the module.


**Disable Plugin Information**

Below every powermail plugin is a short info table with form settings.
You can disable theese information.


**Enable Form caching**

With this setting, you can enable the caching of the form generation,
what speeds up sites with powermail forms in the frontend. On the
other hand, some additional features (like prefilling values from GET
paramter, etc...) are not working any more.


**Enable Merge for l10n\_mode**

All fields with l10n\_mode exclude should change their translation
behaviour to mergeIfNotBlank. This allows you to have different field
values in different languages.


**ElementBrowser replaces IRRE**

Editors can add pages within a form table via IRRE. If this checkbox
is enabled, an element browser replaces the IRRE Relation.


Static Templates
""""""""""""""""

Add powermail static templates for full functions

|img-84|

**Main Template (powermail)**

Main functions and settings for any powermail form.


**Add fancy CSS (powermail)**

If you want to have default CSS settings for powermail forms, check
this template.


**Powermail\_Frontend (powermail)**

If you want to use powermail\_frontend (Pi2), choose this template.


**Marketing Information (powermail)**

If you add this template, a userFunc is added to this page (and all
pages below). The userFunc tracks the behaviour of the user to provide
marketing information (google Searchterm, Funnel, etc...)

