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


Mail Backend Module
^^^^^^^^^^^^^^^^^^^

Open the Mail Backend Module and choose a page in the foldertree. All mails are listed.

|img-77|

The Backend Module will start with the mail listing.

|img-78|

Mail List
"""""""""

If the page contains mails, all mails will be listet. The view is
splitted into two parts (Search Area and List Area).

|img-79|

Search Area
~~~~~~~~~~~

Search Area is useful to filter the mails (of the List Area) and to
manage the Export.
- Click on **Extended Search** opens some more fields which can be used for filtering
- Clock on **Extended Export Settings** allows you to set the export columns

|img-80|

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation

 - :Field:
      Fulltext Search
   :Description:
      This is the main search field for a full text search.
   :Explanation:
      If you enter a searchterm all fields of the mail and of the answers
      are searched by your term (technical note: OR and LIKE %term%)

 - :Field:
      Filter List Button
   :Description:
      Submit Button for search.
   :Explanation:
      This is the main submit button which should be clicked if you're using
      the fulltext search, even if you use some other fields (like Start, Stop, etc...).

 - :Field:
      Start
   :Description:
      Choose a Start Date for the filter list.
   :Explanation:
      A datepicker will be opened on click (if the browser do not respect html5 datetime) to set Date and Time for the beginning of the timeframe.

 - :Field:
      Stop
   :Description:
      Choose a Stop Date for the filter list.
   :Explanation:
      A datepicker will be opened on click (if the browser do not respect html5 datetime) to set Date and Time for the ending of the timeframe.

 - :Field:
      Sender Name
   :Description:
      Search through the sender name field of the stored mail.
   :Explanation:
      All fields are related to each other with OR.

 - :Field:
      Sender Email
   :Description:
      Search through the sender email field of the stored mail.
   :Explanation:
      All fields are related to each other with OR.

 - :Field:
      Subject
   :Description:
      Search through the subjtect field of the stored mail.
   :Explanation:
      All fields are related to each other with OR.

 - :Field:
      Deactivated Mails
   :Description:
      Show only activated or deactivated mails.
   :Explanation:
      Deactivated mails could be interesting if you use Double-Opt-In e.g.

 - :Field:
      Additional Fields
   :Description:
      Clicking on the green Plus Symbol opens a list of all fields (e.g.
      firstname, lastname, email, etc...) from the form.
   :Explanation:
      All fields are related to each other with OR.

Export Area
~~~~~~~~~~~

Export Area gives you the possibility to export your mails in XLS or
CSV format.

|img-81|

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation

 - :Field:
      XLS Icon
   :Description:
      If you want to export the current list in XLS-Format, click the icon.

      XLS-Files can be opened with Microsoft Excel or Open Office (e.g.).
   :Explanation:
      If you filter or sort the list before, the export will only export the
      filtered mails.

      See “Columns in Export File” if you want to change the export file
      columns.

 - :Field:
      CSV Icon
   :Description:
      If you want to export the current list in CSV-Format, click the icon.

      CSV-Files can be opened with Microsoft Excel or Open Office (e.g.).
   :Explanation:
      If you filter or sort the list before, the export will only export the
      filtered mails.

      See “Columns in Export File” if you want to change the export file
      columns.

 - :Field:
      Columns in Export File
   :Description:
      This area shows the columns and the ordering of the rows in the
      export-file.Play around with drag and drop.
   :Explanation:
      Change sorting: Drag and drop a line up or down

      Add row: Choose a line of the “Available Columns” and drop on “Columns
      in Export File”

      Remove row: Drag line and move to the “Available Columns”

 - :Field:
      Available Columns
   :Description:
      This area shows the available columns that can be used in the export
      file.
   :Explanation:
      See Row before for an explanation.

Reporting
"""""""""

Reporting Overview - Choose
- Reporting Form or
- Reporting Marketing

|img-81a|

Reporting (Form)
~~~~~~~~~~~~~~~~

This view helps you to get a small overview over form values.
Filter Mails in the same way as the listing with the filter area.
Below the Filter Area you will see some small diagrams (one diagram for each field in the form on this page).

|img-81b|

Reporting (Marketing)
~~~~~~~~~~~~~~~~~~~~~

This view helps you to get a small overview over the most important information about your visitors.
Filter Mails in the same way as the listing with the filter area.
Below the Filter Area you will see some small diagrams (Referer Domain, Referer URI, Visitors Country, Visitor uses a Mobile Device, Website Language, Browser Language, Page Funnel).
Note: To activate the marketing information, please add the Powermail Marketing Static Template to your Root page.

|img-81c|

Tools
"""""

Tools Overview - Choose
- Form Overview or
- Function Check or
- Form Converter (Admin only)

|img-81d|

Form Overview
~~~~~~~~~~~~~

This view helps you to get a small overview over form values.
Filter Mails in the same way as the listing with the filter area.
Below the Filter Area you will see some small diagrams (one diagram for each field in the form on this page).

|img-81e|

Function Check
~~~~~~~~~~~~~~

This view helps you to get a small overview over the most important information about your visitors.
Filter Mails in the same way as the listing with the filter area.
Below the Filter Area you will see some small diagrams (Referer Domain, Referer URI, Visitors Country, Visitor uses a Mobile Device, Website Language, Browser Language, Page Funnel).
Note: To activate the marketing information, please add the Powermail Marketing Static Template to your Root page.

|img-81f|

Form Converter
~~~~~~~~~~~~~~

This view helps you to get a small overview over the most important information about your visitors.
Filter Mails in the same way as the listing with the filter area.
Below the Filter Area you will see some small diagrams (Referer Domain, Referer URI, Visitors Country, Visitor uses a Mobile Device, Website Language, Browser Language, Page Funnel).
Note: To activate the marketing information, please add the Powermail Marketing Static Template to your Root page.

|img-81g|