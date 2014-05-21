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


Database Model
^^^^^^^^^^^^^^

|img-90|

Write own JavaScript Validation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

We're using the jQuery inline form validation plugin from “position
absolute” – see manual:

`http://www.position-absolute.com/articles/jquery-form-validator-
because-form-validation-is-a-mess/ <http://www.position-
absolute.com/articles/jquery-form-validator-because-form-validation-
is-a-mess/>`_

See our change for checkbox validation:

::

   /**
    * Custom Validation of checkboxes for powermail
    *
    * @param       object          Current Field
    * @param       object          Given Rules
    * @param       int                     Index
    * @param       object          Options
    * @return      string          Error Message
    */
   function checkCheckboxes(field, rules, i, options) {
           var checked = 0; // no checkbox checked at the beginning
           var classes = field.attr('class').split(' ');
           $('.' + classes[1]).each(function() {
                   if ($(this).attr('checked')) {
                           checked = 1;
                   }
           });

           if (!checked) {
                   return options.allrules.checkCheckboxes.alertText;
           }
   }

Call the userFunc from a CSS class:

::

   <input type="checkbox" value="..." name="... id="..." class="validate[funcCall[checkCheckboxes]]" />

