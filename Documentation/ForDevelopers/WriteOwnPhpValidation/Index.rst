

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


.. _writephpvalidation:

Write own PHP Validation
^^^^^^^^^^^^^^^^^^^^^^^^


Introduction
""""""""""""

You can use the CustomValidator (used twice in powermail
FormsController: confirmationAction and createAction) to write your
own field validation after a form submit.

The customValidator is located at
powermail/Classes/Domain/Validator/CustomValidator.php. A signalSlot
Dispatcher within the class waits for your extension.


SignalSlot in CustomValidator
"""""""""""""""""""""""""""""


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


.. ###### END~OF~TABLE ######


Call the Custom Validator from your Extension
"""""""""""""""""""""""""""""""""""""""""""""

Example for TYPO3 4.6 - add a new extension (example key
powermail\_extend).

Example ext\_localconf.php:

::

   $signalSlotDispatcher = t3lib_div::makeInstance('Tx_Extbase_SignalSlot_Dispatcher');
   $signalSlotDispatcher->connect('Tx_Powermail_Domain_Validator_CustomValidator', 'isValid', 'Tx_PowermailExtend_Controller_TestController', 'addInformation', FALSE);

Example Controller file:

::

   class Tx_PowermailExtend_Controller_TestController extends Tx_Extbase_MVC_Controller_ActionController {

           public function addInformation($params, $obj) {

                   // field with uid 12 failed
                   $obj->isValid = false;
                   $obj->setError('mandatory', 12);
           }

   }

