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


Add a new Plugin
^^^^^^^^^^^^^^^^

Choose a page where you want to show a powermail form in the Frontend
and go to the page module. Click on the New Button to add a new
content element to this page and choose “powermail”.

|img-72|

Plugin Settings
"""""""""""""""

You will find the plugin settings within the tab “Plugin”. In this
area you see another four tabs (Main Settings, Receiver, Sender,
Submit Page).


Main Settings
~~~~~~~~~~~~~


Example Configuration
'''''''''''''''''''''

|img-73|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Choose a Powermail Form

   Description
         Choose an existing powermail form.

   Explanation
         You have to store a form first (see manual section above) by clicknig
         the plus symbol.

   Tab
         Main Settings


.. container:: table-row

   Field
         Confirmation Page

   Description
         Enable a confirmation page.

   Explanation
         This enables a confirmation page (Are theese values correct?) to the
         frontend.

   Tab
         Main Settings


.. container:: table-row

   Field
         Double-Opt-In

   Description
         Add Double-Opt-In feature to this form.

   Explanation
         A user has to confirm his email by clicking a link in a mail first
         before the main mail is sent.Note: You can overwrite the email to the
         user by administrators email address.

   Tab
         Main Settings


.. container:: table-row

   Field
         Step by step

   Description
         Enable morestep form.

   Explanation
         Each page (fieldset) will be splittet to one page in the frontend.
         With JavaScript the user can switch between the pages.

   Tab
         Main Settings


.. container:: table-row

   Field
         Where to save Mails

   Description
         Choose a page where to store the mails in the database.

   Explanation
         You can select a page or a folder. Leaving this empty will store the
         mails on the same page.

   Tab
         Main Settings


.. ###### END~OF~TABLE ######


Receiver
~~~~~~~~


Example Configuration
'''''''''''''''''''''

|img-74|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Receivers Name

   Description
         Add the name of the main receiver name.

   Explanation
         \- Add a static value

         \- Add a variable like {firstname}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Receiver


.. container:: table-row

   Field
         Receivers Mail

   Description
         Add the email address of one or more receivers

   Explanation
         \- Add one or more static values (split with a new line)

         \- Add a variable like {email}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Receiver


.. container:: table-row

   Field
         Frontend User Group

   Description
         Choose a Frontend User Group.

   Explanation
         Select an existing group to send the mail to all users of a given
         group.

   Tab
         Receiver


.. container:: table-row

   Field
         Subject

   Description
         Subject for mail to receiver.

   Explanation
         \- Add a static value

         \- Add a variable like {firstname}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Receiver


.. container:: table-row

   Field
         Bodytext

   Description
         Add some text for the mail to the receiver.

   Explanation
         \- Add a static value

         \- Add {powermail\_all} to get all values from the form in one table
         (with labels)

         \- Add a variable like {firstname}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Receiver


.. ###### END~OF~TABLE ######


Sender
~~~~~~


Example Configuration
'''''''''''''''''''''

|img-75|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Senders Name

   Description
         Add the name of the sender.

   Explanation
         \- Add a static value

         \- Add a variable like {firstname}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Sender


.. container:: table-row

   Field
         Senders Email

   Description
         Add the email address of the sender.

   Explanation
         \- Add one or more static values (split with a new line)

         \- Add a variable like {email}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Sender


.. container:: table-row

   Field
         Subject

   Description
         Subject for mail to sender. Leaving subject empty disables the mail to
         the sender.

   Explanation
         \- Add a static value

         \- Add a variable like {firstname}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Sender


.. container:: table-row

   Field
         Bodytext

   Description
         Add some text for the mail to the sender.

   Explanation
         \- Add a static value

         \- Add {powermail\_all} to get all values from the form in one table
         (with labels)

         \- Add a variable like {firstname}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Sender


.. ###### END~OF~TABLE ######


Submit Page
~~~~~~~~~~~


Example Configuration
'''''''''''''''''''''

|img-76|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Text on submit page

   Description
         Add some text for submit message.

   Explanation
         \- Add a static value

         \- Add {powermail\_all} to get all values from the form in one table
         (with labels)

         \- Add a variable like {firstname}

         \- Add a viewhelper call like
         {f:cObject(typoscriptObjectPath:'lib.test')} to get a value from
         TypoScript or a userFunc

         \- or mix dynamic and static values

   Tab
         Submit Page


.. container:: table-row

   Field
         Redirect

   Description
         Add a redirect target instead of adding text (see row above).

   Explanation
         As soon as you enter a value, the user will be redirected to a target
         on submit (internal page, external URL, document, mail address)

   Tab
         Submit Page


.. ###### END~OF~TABLE ######

