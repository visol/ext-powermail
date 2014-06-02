.. include:: Images.txt

.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. ==================================================
.. DEFINE SOME TEXTROLES
.. --------------------------------------------------
.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
:class:  typoscript
.. role::   php(code)


Add own Fields
^^^^^^^^^^^^^^

Per default, there are a lot of Field-Types available in Powermail.

|img-typeselection|

If you want to add further fields, you can do this with a little bit of Page TSConfig.
::

   tx_powermail.flexForm.type.addFieldOptions.new = New Field

With this TSConfig, a new Option is available:

|img-typeselection2|

If an editor chose the new field, powermail searches by default for a Partial with Name New.html (Default Path is powermail/Resources/Private/Partials/Form/New.html).

Because you should not modify anything within an extension-folder (because of upcoming extension-updates), you should Create a new File in your fileadmin folder - e.g.: fileadmin/powermail/Partials/Form/New.html

Example Content:
::

   <div>
      <h2>This is a complete new Field</h2>
   </div>

Let's take TypoScript Setup to tell powermail, where to find the new partial:
::

   plugin.tx_powermail.view {
      partialRootPath >
      partialRootPaths {
         10 = EXT:powermail/Resources/Private/Partials/
         20 = fileadmin/powermail/Partials/
      }
   }
