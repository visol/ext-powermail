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


Add a new Form
--------------

Choose a page (could also be a folder) where to store the new form-record and change to the list view. Click
on the New Button to add a new record to this page and choose “Forms”.

|img-9|

Form Settings
^^^^^^^^^^^^^

|img-10|

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a title for your form.
   :Explanation:
      The title is used to find the form in the backend. You can also show the title in the frontend.
   :Tab:
      General

 - :Field:
      Pages
   :Description:
      Add one or more pages to a form.
   :Explanation:
      A form collects a couple of pages. You need minimum 1 page to show a form. If you choose a multistep form, every step is splitted in one page.
   :Tab:
      General

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries and switch the frontend layout of your form.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this form should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable a form with all pages and fields.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access


Pages Settings
^^^^^^^^^^^^^^

|img-11|

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a title for your page.
   :Explanation:
      The title is used to find the page in the backend. You can also show the title in the frontend.
   :Tab:
      General

 - :Field:
      Fields
   :Description:
      Add one or more fields to this page.
   :Explanation:
      A page collects a couple of fields. You need minimum 1 field to show a form.
   :Tab:
      General

 - :Field:
      Note
   :Description:
      Just a small Note.
   :Explanation:
      This note shows you if there is no Sendermail or Sendername marked in the fields. Without this information powermail will set default values for the Sendername and Senderemail. If you are aware of this and you don't want to see this information in future (for this form), you can disable this note.
   :Tab:
      General

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this record should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable this record.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access


Field Settings
^^^^^^^^^^^^^^


General
"""""""


Backend Configuration Example
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

|img-12|

|img-13|

Explanation
~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a label for this field.
   :Explanation:
      The label is shown in the frontend near to this field.
   :Tab:
      General

 - :Field:
      Type
   :Description:
      Choose a fieldtype.
   :Explanation:
      See explanation below for a special fieldtype. Different fields are related to some fieldtypes – not all fields are shown on every type.
   :Tab:
      General

 - :Field:
      Email of sender
   :Description:
      Check this if this field contains the email of the sender.
   :Explanation:
      This is needed to set the correct sender-email-address. If there is no field marked as Senderemail within the current form, powermail will use a default value for the Senderemail.
   :Tab:
      General

 - :Field:
      Name of sender
   :Description:
      Check this if this field contains the name (or a part of the name) of the sender.
   :Explanation:
      This is needed to set the correct sender-name. If there is no field marked as Sendername within the current form, powermail will use a default value for the Sendername.
   :Tab:
      General

 - :Field:
      Mandatory Field
   :Description:
      This field must contain input.
   :Explanation:
      Check this if the field must contain input, otherwise submitting the form is not possible.
   :Tab:
      Extended

 - :Field:
      Validation
   :Description:
      Validate the user input with a validator.
   :Explanation:
      Possible Validation Methods are: Email, URL, Phone, Numbers only, Letters only, Min Number, Max Number, Range, Length, Pattern (RegEx)
   :Tab:
      Extended

 - :Field:
      Prefill with value
   :Description:
      Prefill field value with a static content.
   :Explanation:
      Other possibilities to prefill a field: TypoScript, GET or POST params
   :Tab:
      Extended

 - :Field:
      Value from logged in Frontend User
   :Description:
      Check if field should be filled from the FE_Users table of a logged in fe_user.
   :Explanation:
      This value overwrites a static value, if set.
   :Tab:
      Extended

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Variables – Individual Fieldname
   :Description:
      This is a marker of this field.
   :Explanation:
      Use a field variable with {marker} in any RTE or HTML-Template. The marker name is equal in any language.
   :Tab:
      Extended

 - :Field:
      Add own Variable
   :Description:
      Check this, if you want to set your own marker (see row before).
   :Explanation:
      After checking this button, TYPO3 ask you to reload. After a reload, you see a new field for setting an own marker.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this record should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable this record.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access


Field Types
~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :HTML:
      HTML
   :Category:
      Tab
   :Example:
      Example
   :Ref:
      Details

 - :Field:
      Textfield (input)
   :Description:
      Simple text field (one line)
   :HTML:
      <input type=”text” />
   :Category:
      Standard
   :Example:
      |img-14|
   :Ref:
      :ref:`input`

 - :Field:
      Textfield with more rows (Textarea)
   :Description:
      Text field with more lines
   :HTML:
      <textarea></textarea>
   :Category:
      Standard
   :Example:
      |img-15|
   :Ref:
      :ref:`textarea`

 - :Field:
      Selectfield
   :Description:
      Selector box (Dropdown)
   :HTML:
      <select><option>X</option></select>
   :Category:
      Standard
   :Example:
      |img-16|
   :Ref:
      :ref:`select`

 - :Field:
      Checkboxes
   :Description:
      Checkbox (Possibility to select more than only one)
   :HTML:
      <input type=”checkbox” />
   :Category:
      Standard
   :Example:
      |img-17|
   :Ref:
      :ref:`check`

 - :Field:
      Radiobuttons
   :Description:
      Radio Buttons (Possibility to check only one)
   :HTML:
      <input type=”radio” />
   :Category:
      Standard
   :Example:
      |img-18|
   :Ref:
      :ref:`radio`

 - :Field:
      Submit
   :Description:
      Send Form
   :HTML:
      <input type=”submit” />
   :Category:
      Standard
   :Example:
      |img-19|
   :Ref:
      :ref:`submit`

 - :Field:
      Captcha
   :Description:
      Captcha Check against spam
   :HTML:
      <input type=”text” />
   :Category:
      Extended
   :Example:
      |img-20|
   :Ref:
      :ref:`captcha`

 - :Field:
      Reset
   :Description:
      Reset cleans all fieldvalues in the form
   :HTML:
      <input type=”reset” />
   :Category:
      Extended
   :Example:
      |img-21|
   :Ref:
      :ref:`reset`

 - :Field:
      Show some text
   :Description:
      This field let you show some text in the form
   :HTML:
      This is a Test
   :Category:
      Extended
   :Example:
      |img-22|
   :Ref:
      :ref:`text`

 - :Field:
      Content Element
   :Description:
      Show an existing Content Element
   :HTML:
      Text with <img src=”...” />
   :Category:
      Extended
   :Example:
      |img-23|
   :Ref:
      :ref:`content`

 - :Field:
      Show HTML
   :Description:
      Add some html text. Text is automaticle parsed through a removeXSS-Function. If you are aware of what you are doing, you can disable the removeXSS function with TypoScript constants.
   :HTML:
      This is a <b>Test</b>
   :Category:
      Extended
   :Example:
      |img-24|
   :Ref:
      :ref:`html`

 - :Field:
      Password Field
   :Description:
      Two fields for a password check
   :HTML:
      <input type=”password” /> <input type=”password” />
   :Category:
      Extended
   :Example:
      |img-25|
   :Ref:
      :ref:`password`

 - :Field:
      File Upload
   :Description:
      Upload one or more files
   :HTML:
      <input type=”file” />
   :Category:
      Extended
   :Example:
      |img-26|
   :Ref:
      :ref:`file`

 - :Field:
      Hidden Field
   :Description:
      Renders a hidden field, where you can store some additional information within the form.
   :HTML:
      <input type=”hidden” />
   :Category:
      Extended
   :Example:
      -
   :Ref:
      :ref:`hidden`

 - :Field:
      Date
   :Description:
      Datepicker field (Date, Datetime or Time)
   :HTML:
      <input type=”date” />
   :Category:
      Extended
   :Example:
      |img-27|
   :Ref:
      :ref:`date`

 - :Field:
      Countryselection
   :Description:
      Choose a Country
   :HTML:
      <select><option>France</option><option>Germany</option></select>
   :Category:
      Extended
   :Example:
      |img-27b|
   :Ref:
      :ref:`country`

 - :Field:
      Location
   :Description:
      Location field. Browser will ask the user if it's ok to fill the field
      with his current location.
   :HTML:
      <input type=”text” />
   :Category:
      Extended
   :Example:
      |img-28|
   :Ref:
      :ref:`location`

 - :Field:
      TypoScript
   :Description:
      Fill values from TypoScript
   :HTML:
      This is a <b>Test</b>
   :Category:
      Extended
   :Example:
      |img-24|
   :Ref:
      :ref:`typoscript`


.. _input:

Textfield (Input)
~~~~~~~~~~~~~~~~~

Frontend Output Example
'''''''''''''''''''''''

|img-29|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-12|

|img-13|

Explanation
'''''''''''



.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a label for this field.
   :Explanation:
      The label is shown in the frontend near to this field.
   :Tab:
      General

 - :Field:
      Type
   :Description:
      Choose a fieldtype.
   :Explanation:
      See explanation below for a special fieldtype. Different fields are related to some fieldtypes – not all fields are shown on every type.
   :Tab:
      General

 - :Field:
      Email of sender
   :Description:
      Check this if this field contains the email of the sender.
   :Explanation:
      This is needed to set the correct sender-email-address. If there is no field marked as Senderemail within the current form, powermail will use a default value for the Senderemail.
   :Tab:
      General

 - :Field:
      Name of sender
   :Description:
      Check this if this field contains the name (or a part of the name) of the sender.
   :Explanation:
      This is needed to set the correct sender-name. If there is no field marked as Sendername within the current form, powermail will use a default value for the Sendername.
   :Tab:
      General

 - :Field:
      Mandatory Field
   :Description:
      This field must contain input.
   :Explanation:
      Check this if the field must contain input, otherwise submitting the form is not possible.
   :Tab:
      Extended

 - :Field:
      Validation
   :Description:
      Validate the user input with a validator.
   :Explanation:
      Possible Validation Methods are: Email, URL, Phone, Numbers only, Letters only, Min Number, Max Number, Range, Length, Pattern (RegEx)
   :Tab:
      Extended

 - :Field:
      Prefill with value
   :Description:
      Prefill field value with a static content.
   :Explanation:
      Other possibilities to prefill a field: TypoScript, GET or POST params
   :Tab:
      Extended

 - :Field:
      Value from logged in Frontend User
   :Description:
      Check if field should be filled from the FE_Users table of a logged in fe_user.
   :Explanation:
      This value overwrites a static value, if set.
   :Tab:
      Extended

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Variables – Individual Fieldname
   :Description:
      This is a marker of this field.
   :Explanation:
      Use a field variable with {marker} in any RTE or HTML-Template. The marker name is equal in any language.
   :Tab:
      Extended

 - :Field:
      Add own Variable
   :Description:
      Check this, if you want to set your own marker (see row before).
   :Explanation:
      After checking this button, TYPO3 ask you to reload. After a reload, you see a new field for setting an own marker.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this record should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable this record.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access


.. _textarea:

Text with more rows (Textarea)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-30|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-31|

|img-32|


Explanation
'''''''''''


.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a label for this field.
   :Explanation:
      The label is shown in the frontend near to this field.
   :Tab:
      General

 - :Field:
      Type
   :Description:
      Choose a fieldtype.
   :Explanation:
      See explanation below for a special fieldtype. Different fields are related to some fieldtypes – not all fields are shown on every type.
   :Tab:
      General

 - :Field:
      Email of sender
   :Description:
      Check this if this field contains the email of the sender.
   :Explanation:
      This is needed to set the correct sender-email-address. If there is no field marked as Senderemail within the current form, powermail will use a default value for the Senderemail.
   :Tab:
      General

 - :Field:
      Name of sender
   :Description:
      Check this if this field contains the name (or a part of the name) of the sender.
   :Explanation:
      This is needed to set the correct sender-name. If there is no field marked as Sendername within the current form, powermail will use a default value for the Sendername.
   :Tab:
      General

 - :Field:
      Mandatory Field
   :Description:
      This field must contain input.
   :Explanation:
      Check this if the field must contain input, otherwise submitting the form is not possible.
   :Tab:
      Extended

 - :Field:
      Validation
   :Description:
      Validate the user input with a validator.
   :Explanation:
      Possible Validation Methods are: Email, URL, Phone, Numbers only, Letters only, Min Number, Max Number, Range, Length, Pattern (RegEx)
   :Tab:
      Extended

 - :Field:
      Prefill with value
   :Description:
      Prefill field value with a static content.
   :Explanation:
      Other possibilities to prefill a field: TypoScript, GET or POST params
   :Tab:
      Extended

 - :Field:
      Value from logged in Frontend User
   :Description:
      Check if field should be filled from the FE_Users table of a logged in fe_user.
   :Explanation:
      This value overwrites a static value, if set.
   :Tab:
      Extended

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Variables – Individual Fieldname
   :Description:
      This is a marker of this field.
   :Explanation:
      Use a field variable with {marker} in any RTE or HTML-Template. The marker name is equal in any language.
   :Tab:
      Extended

 - :Field:
      Add own Variable
   :Description:
      Check this, if you want to set your own marker (see row before).
   :Explanation:
      After checking this button, TYPO3 ask you to reload. After a reload, you see a new field for setting an own marker.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this record should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable this record.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access


.. _select:

Selectfield
~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-33|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-34|

|img-35|

Explanation
'''''''''''

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
     Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a label for this field.
   :Explanation:
      The label is shown in the frontend near to this field.
   :Tab:
      General

 - :Field:
      Type
   :Description:
      Choose a fieldtype.
   :Explanation:
      See explanation below for a special fieldtype. Different fields are related to some fieldtypes – not all fields are shown on every type.
   :Tab:
      General

 - :Field:
      Options
   :Description:
      Options to select
   :Explanation:
      Separate each with a new line. **Note: see following
      table for examples, how to preselect or clean a value**
   :Tab:
      General

 - :Field:
      Email of sender
   :Description:
      Check this if this field contains the email of the sender.
   :Explanation:
      This is needed to set the correct sender-email-address. If there is no field marked as Senderemail within the current form, powermail will use a default value for the Senderemail.
   :Tab:
      General

 - :Field:
      Name of sender
   :Description:
      Check this if this field contains the name (or a part of the name) of the sender.
   :Explanation:
      This is needed to set the correct sender-name. If there is no field marked as Sendername within the current form, powermail will use a default value for the Sendername.
   :Tab:
      General

 - :Field:
      Mandatory Field
   :Description:
      This field must contain input.
   :Explanation:
      Check this if the field must contain input, otherwise submitting the form is not possible.
   :Tab:
      Extended

 - :Field:
      Value from logged in Frontend User
   :Description:
      Check if field should be filled from the FE_Users table of a logged in fe_user.
   :Explanation:
      This value overwrites a static value, if set.
   :Tab:
      Extended

 - :Field:
      Create from TypoScript
   :Description:
      Fill Options from TypoScript
   :Explanation:
      If you want to create your options (see above) from TypoScript, you can use this field. Please split each line in your TypoScript with [\n]
   :Tab:
      Extended

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Multiselect
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Variables – Individual Fieldname
   :Description:
      This is a marker of this field.
   :Explanation:
      Use a field variable with {marker} in any RTE or HTML-Template. The marker name is equal in any language.
   :Tab:
      Extended

 - :Field:
      Add own Variable
   :Description:
      Check this, if you want to set your own marker (see row before).
   :Explanation:
      After checking this button, TYPO3 ask you to reload. After a reload, you see a new field for setting an own marker.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this record should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable this record.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access


Option examples for selectbox
'''''''''''''''''''''''''''''

.. t3-field-list-table::
 :header-rows: 1

 - :Example:
     Example option
   :HTML:
     Generated HTML code in Frontend

 - :Example:
      Red
   :HTML:
      <option value=”Red”>Red</option>

 - :Example:
      Yellow \| 1
   :HTML:
      <option value=”1”>Yellow</option>

 - :Example:
      Blue \|
   :HTML:
      <option value=””>Blue</option>

 - :Example:
      Black Shoes \| black \| \*
   :HTML:
      <option value=”black” selected=”selected”>Black Shoes</option>

 - :Example:
      White \| \| \*
   :HTML:
      <option value=”” selected=”selected”>White</option>

 - :Example:
      Please choose... \|
      | red
      | blue
   :HTML:
      | <option value=””>Please choose...</option>
      | <option>red</option>
      | <option>blue</option>


.. _check:

Checkboxes
~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-36|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-37|

|img-38|

Explanation
'''''''''''

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
     Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a label for this field.
   :Explanation:
      The label is shown in the frontend near to this field.
   :Tab:
      General

 - :Field:
      Type
   :Description:
      Choose a fieldtype.
   :Explanation:
      See explanation below for a special fieldtype. Different fields are related to some fieldtypes – not all fields are shown on every type.
   :Tab:
      General

 - :Field:
      Options
   :Description:
      Options to check
   :Explanation:
      Separate each with a new line. **Note: see following
      table for examples, how to precheck or clean a value**
   :Tab:
      General

 - :Field:
      Email of sender
   :Description:
      Check this if this field contains the email of the sender.
   :Explanation:
      This is needed to set the correct sender-email-address. If there is no field marked as Senderemail within the current form, powermail will use a default value for the Senderemail.
   :Tab:
      General

 - :Field:
      Name of sender
   :Description:
      Check this if this field contains the name (or a part of the name) of the sender.
   :Explanation:
      This is needed to set the correct sender-name. If there is no field marked as Sendername within the current form, powermail will use a default value for the Sendername.
   :Tab:
      General

 - :Field:
      Mandatory Field
   :Description:
      This field must contain input.
   :Explanation:
      Check this if the field must contain input, otherwise submitting the form is not possible.
   :Tab:
      Extended

 - :Field:
      Value from logged in Frontend User
   :Description:
      Check if field should be filled from the FE_Users table of a logged in fe_user.
   :Explanation:
      This value overwrites a static value, if set.
   :Tab:
      Extended

 - :Field:
      Create from TypoScript
   :Description:
      Fill Options from TypoScript
   :Explanation:
      If you want to create your options (see above) from TypoScript, you can use this field. Please split each line in your TypoScript with [\n]
   :Tab:
      Extended

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Variables – Individual Fieldname
   :Description:
      This is a marker of this field.
   :Explanation:
      Use a field variable with {marker} in any RTE or HTML-Template. The marker name is equal in any language.
   :Tab:
      Extended

 - :Field:
      Add own Variable
   :Description:
      Check this, if you want to set your own marker (see row before).
   :Explanation:
      After checking this button, TYPO3 ask you to reload. After a reload, you see a new field for setting an own marker.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this record should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable this record.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access


Option examples for checkbox
''''''''''''''''''''''''''''

.. t3-field-list-table::
 :header-rows: 1

 - :Example:
     Example option
   :HTML:
     Generated HTML code in Frontend

 - :Example:
      Red
   :HTML:
      <label>Red</label><input value=”Red” />

 - :Example:
      Yellow \| 1
   :HTML:
      <label>Yellow</label><input value=”1” />

 - :Example:
      Blue \|
   :HTML:
      <label>Blue</label><input value=”” />

 - :Example:
      Black Shoes \| black \| \*
   :HTML:
      <label>Black Shoes</label><input value=”black” checked=”checked” />

 - :Example:
      White \| \| \*
   :HTML:
      <label>White</label><input value=”” checked=”checked” />

 - :Example:
      | Red Shoes \| red \| \*
      | Yellow Shoes \| yellow \| \*
   :HTML:
      | <label>Red Shoes</label><input value=”red” checked=”checked” />
      | <label>Yellow Shoes</label><input value=”yellow” checked=”checked” />


.. _radio:

Radiobuttons
~~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-39|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-40|

|img-41|

Explanation
'''''''''''

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
     Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a label for this field.
   :Explanation:
      The label is shown in the frontend near to this field.
   :Tab:
      General

 - :Field:
      Type
   :Description:
      Choose a fieldtype.
   :Explanation:
      See explanation below for a special fieldtype. Different fields are related to some fieldtypes – not all fields are shown on every type.
   :Tab:
      General

 - :Field:
      Options
   :Description:
      Options to check
   :Explanation:
      Separate each with a new line. **Note: see following
      table for examples, how to precheck or clean a value**
   :Tab:
      General

 - :Field:
      Email of sender
   :Description:
      Check this if this field contains the email of the sender.
   :Explanation:
      This is needed to set the correct sender-email-address. If there is no field marked as Senderemail within the current form, powermail will use a default value for the Senderemail.
   :Tab:
      General

 - :Field:
      Name of sender
   :Description:
      Check this if this field contains the name (or a part of the name) of the sender.
   :Explanation:
      This is needed to set the correct sender-name. If there is no field marked as Sendername within the current form, powermail will use a default value for the Sendername.
   :Tab:
      General

 - :Field:
      Mandatory Field
   :Description:
      This field must contain input.
   :Explanation:
      Check this if the field must contain input, otherwise submitting the form is not possible.
   :Tab:
      Extended

 - :Field:
      Value from logged in Frontend User
   :Description:
      Check if field should be filled from the FE_Users table of a logged in fe_user.
   :Explanation:
      This value overwrites a static value, if set.
   :Tab:
      Extended

 - :Field:
      Create from TypoScript
   :Description:
      Fill Options from TypoScript
   :Explanation:
      If you want to create your options (see above) from TypoScript, you can use this field. Please split each line in your TypoScript with [\n]
   :Tab:
      Extended

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Variables – Individual Fieldname
   :Description:
      This is a marker of this field.
   :Explanation:
      Use a field variable with {marker} in any RTE or HTML-Template. The marker name is equal in any language.
   :Tab:
      Extended

 - :Field:
      Add own Variable
   :Description:
      Check this, if you want to set your own marker (see row before).
   :Explanation:
      After checking this button, TYPO3 ask you to reload. After a reload, you see a new field for setting an own marker.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this record should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable this record.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

Option examples for radio buttons
'''''''''''''''''''''''''''''''''

.. t3-field-list-table::
 :header-rows: 1

 - :Example:
     Example option
   :HTML:
     Generated HTML code in Frontend

 - :Example:
      Red
   :HTML:
      <label>Red</label><input value=”Red” />

 - :Example:
      Yellow \| 1
   :HTML:
      <label>Yellow</label><input value=”1” />

 - :Example:
      Blue \|
   :HTML:
      <label>Blue</label><input value=”” />

 - :Example:
      Black Shoes \| black \| \*
   :HTML:
      <label>Black Shoes</label><input value=”black” checked=”checked” />

 - :Example:
      White \| \| \*
   :HTML:
      <label>White</label><input value=”” checked=”checked” />

 - :Example:
      | Red Shoes \| red \| \*
      | Yellow Shoes \| yellow \| \*
   :HTML:
      | <label>Red Shoes</label><input value=”red” checked=”checked” />
      | <label>Yellow Shoes</label><input value=”yellow” checked=”checked” />


.. _submit:

Submit
~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-42|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-43|

|img-44|

Explanation
'''''''''''

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
     Description
   :Explanation:
      Explanation
   :Tab:
      Tab

 - :Field:
      Title
   :Description:
      Add a label for this field.
   :Explanation:
      The label is shown in the frontend near to this field.
   :Tab:
      General

 - :Field:
      Type
   :Description:
      Choose a fieldtype.
   :Explanation:
      See explanation below for a special fieldtype. Different fields are related to some fieldtypes – not all fields are shown on every type.
   :Tab:
      General

 - :Field:
      Layout
   :Description:
      Choose a layout.
   :Explanation:
      This adds a CSS-Class to the frontend output. Administrator can add, remove or rename some of the entries.
   :Tab:
      Extended

 - :Field:
      Variables – Individual Fieldname
   :Description:
      This is a marker of this field.
   :Explanation:
      Use a field variable with {marker} in any RTE or HTML-Template. The marker name is equal in any language.
   :Tab:
      Extended

 - :Field:
      Add own Variable
   :Description:
      Check this, if you want to set your own marker (see row before).
   :Explanation:
      After checking this button, TYPO3 ask you to reload. After a reload, you see a new field for setting an own marker.
   :Tab:
      Extended

 - :Field:
      Language
   :Description:
      Choose a language.
   :Explanation:
      Choose in which frontend language this record should be rendered.
   :Tab:
      Access

 - :Field:
      Hide
   :Description:
      Disable the form
   :Explanation:
      Enable or disable this record.
   :Tab:
      Access

 - :Field:
      Start
   :Description:
      Startdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

 - :Field:
      Stop
   :Description:
      Stopdate for this record.
   :Explanation:
      Same function as known from default content elements or pages in TYPO3.
   :Tab:
      Access

.. _captcha:

Captcha Field
~~~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-45|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-46|

|img-47|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _reset:

Reset
~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-48|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-49|

|img-50|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Field:

   Description
         Description:

   Explanation
         Explanation:

   Tab
         Tab:


.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _text:

Show some Text
~~~~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-51|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-52|

|img-53|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Add some text

   Description
         This is the field for the text

   Explanation
         HTML Tags are not allowed for security reasons

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _content:

Content Element
~~~~~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-54|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-55|

|img-56|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Select Content Element

   Description
         Select an existing content element to show.

   Explanation
         Add a content element before and select it in the popup.

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _html:

Show HTML
~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-57|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-58|

|img-59|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Add some text

   Description
         This is the field for the html tags and text

   Explanation
         HTML Tags are not allowed for security reasons by default. Can be
         enabled from the administrator by TypoScript constants.

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _password:

Password Field
~~~~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-25|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-60|

|img-59|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _file:

File Upload
~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-61|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-62|

|img-59|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _hidden:

Hidden Field
~~~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

Because it is "hidden", there is no visible frontend output.


Backend Configuration Example
'''''''''''''''''''''''''''''

|img-63|

|img-64|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Email of sender

   Description
         Check this if the field contains the email of the sender.

   Explanation
         This is needed to set the correct sender-email-address

   Tab
         General


.. container:: table-row

   Field
         Name of sender

   Description
         Check this if the field contains a part of the sender-name.

   Explanation
         This is needed to set the correct sender-name

   Tab
         General


.. container:: table-row

   Field
         Prefill with value

   Description
         Prefill field value with a static content.

   Explanation
         Other possibilities to prefill a field:- With TypoScript- With GET or
         POST params

   Tab
         Extended


.. container:: table-row

   Field
         Prefill with Value from FE User

   Description
         Check if field should be filled from the FE\_Users table of a logged
         in fe\_user.

   Explanation
         This value overwrites a static value

   Tab
         Extended


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _date:

Date
~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-65|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-66|

|img-67|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Mandatory Field

   Description
         Check this if the field should contain a content.

   Explanation
         If the field is empty in frontend, the form will not be sent. Check
         with JavaScript and PHP.

   Tab
         Extended


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _location:

Location
~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-68|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-69|

|img-59|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation


   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######


.. _typoscript:

TypoScript
~~~~~~~~~~


Frontend Output Example
'''''''''''''''''''''''

|img-70|

Backend Configuration Example
'''''''''''''''''''''''''''''

|img-71|

|img-59|

Explanation
'''''''''''

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Title

   Description
         Add a value for this field.

   Explanation
         The value is shown in the button.

   Tab
         General


.. container:: table-row

   Field
         Type

   Description
         Choose another fieldtype.

   Explanation
         A change forces a browser reload.

   Tab
         General


.. container:: table-row

   Field
         TypoScript Path

   Description
         Add TypoScript path to show in frontend.

   Explanation
         Example TypoScript could be:lib.test = TEXT

         lib.test.value = xyz

   Tab
         General


.. container:: table-row

   Field
         Variables – Individual Fieldname

   Description
         This is a marker of this field.

   Explanation
         Use a field variable with {marker} in any RTE or HTML-Template. The
         marker name is equal in any language.

   Tab
         Extended


.. container:: table-row

   Field
         Add own Variable

   Description
         Check this, if you want to set your own marker (see row before).

   Explanation
         After checking this button, TYPO3 ask you to reload. After a reload,
         you see a new field for setting an own marker.

   Tab
         Extended


.. container:: table-row

   Field
         Language

   Description
         Choose a language.

   Explanation
         None

   Tab
         Access


.. container:: table-row

   Field
         Hide

   Description
         Disable the form

   Explanation
         Enable or disable a form with all pages and fields

   Tab
         Access


.. container:: table-row

   Field
         Start

   Description
         Startdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. container:: table-row

   Field
         Stop

   Description
         Stopdate for a form

   Explanation
         Same function as known from default content elements or pages in TYPO3

   Tab
         Access


.. ###### END~OF~TABLE ######
