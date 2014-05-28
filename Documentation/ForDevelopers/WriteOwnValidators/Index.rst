

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


.. _writejavascriptvalidation:

Write own JavaScript Validation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Introduction
""""""""""""

Since powermail 2.1 parsley.js (Version 2.0) is in use

`http://parsleyjs.org/ <http://parsleyjs.org/>`_

**How to add validation with parsley.js to a form**

Example form with a reqired and an email field. In addition to HTML5, this input fields are validated with parsley:
::

   <form data-parsley-validate>
        <input type="text" name="firstname" required="required" />

        <input type="email" name="email" />

        <input type="submit" />
   </form>

**Write an own JavaScript Validation**

::

    <input type="text" data-parsley-multiple="3" data-parsley-error-message="Please try again" />
        [...]
    <script type="text/javascript">
        window.ParsleyValidator
            .addValidator('multiple', function (value, requirement) {
                return 0 === value % requirement;
            }, 32)
            .addMessage('en', 'multiple', 'This value should be a multiple of %s');
    </script>



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

.. t3-field-list-table::
 :header-rows: 1

 - :Class:
      Signal Class Name
   :Name:
      Signal Name
   :File:
      Located in File
   :Method:
      Located in Method

 - :Class:
      \In2code\Powermail\Domain\Validator\CustomValidator
   :Name:
      isValid
   :File:
      CustomValidator.php
   :Method:
      isValid()

Call the Custom Validator from your Extension
"""""""""""""""""""""""""""""""""""""""""""""

Add a new extension (example key powermail_extend).

Example ext_localconf.php:

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

