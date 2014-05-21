

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


Signal Slots
^^^^^^^^^^^^

Powermail offers a lot of SignalSlots (Extbase pendant to Hooks) to
extend the functions from your extension. Please use forge.typo3.org
if you need another signalSlot.


.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Signal Class Name
         Tx\_Powermail\_Domain\_Validator\_CustomValidator

   Signal Name
         isValid

   Located in File
         CustomValidator.php

   Located in Method
         isValid()

   Description
         Extend Powermail with your own validation


.. container:: table-row

   Signal Class Name
         Tx\_Powermail\_Controller\_FormsController

   Signal Name
         formActionBeforeRenderView

   Located in File
         FormsController.php

   Located in Method
         formAction()

   Description
         Slot is called before the form is rendered


.. container:: table-row

   Signal Class Name
         Tx\_Powermail\_Controller\_FormsController

   Signal Name
         confirmationActionBeforeRenderView

   Located in File
         FormsController.php

   Located in Method
         confirmationAction()

   Description
         Slot is called before the confirmation view is rendered


.. container:: table-row

   Signal Class Name
         Tx\_Powermail\_Controller\_FormsController

   Signal Name
         createActionBeforeRenderView

   Located in File
         FormsController.php

   Located in Method
         createAction()

   Description
         Slot is called before the answered are stored and the mails are sent


.. container:: table-row

   Signal Class Name
         Tx\_Powermail\_Controller\_FormsController

   Signal Name
         createActionAfterMailDbSaved

   Located in File
         FormsController.php

   Located in Method
         createAction()

   Description
         Slot ist called directly after the mail was stored in the db


.. container:: table-row

   Signal Class Name
         Tx\_Powermail\_Controller\_FormsController

   Signal Name
         createActionAfterSubmitView

   Located in File
         FormsController.php

   Located in Method
         createAction()

   Description
         Slot is called after the thx message was rendered (Only available if
         no redirect was activated – if you use a redirect, please choose Slot
         createActionAfterMailDbSaved)


.. container:: table-row

   Signal Class Name
         Tx\_Powermail\_Controller\_FormsController

   Signal Name
         optinConfirmActionBeforeRenderView

   Located in File
         FormsController.php

   Located in Method
         optinConfirmAction()

   Description
         Slot is called before the optin confirmation view is rendered (only if

         Double-Opt-In is in use)


.. container:: table-row

   Signal Class Name
         Tx\_Powermail\_Controller\_FormsController

   Signal Name
         initializeActionSettings

   Located in File
         FormsController.php

   Located in Method
         initializeAction()

   Description
         Change Settings from Flexform or TypoScript before Action is called


.. ###### END~OF~TABLE ######

