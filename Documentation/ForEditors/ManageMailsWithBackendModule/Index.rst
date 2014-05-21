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


Manage Mails with Backend Module
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Choose a page where some mails from powermail are stored and open the
powermail Backend Module on the left side.

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

|img-80|

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Fulltext Search

   Description
         This is the main search field for a full text search.

   Additional Explanation
         If you enter a searchterm all fields of the mail and of the answers
         are searched by your term (technical note: OR and LIKE %term%)


.. container:: table-row

   Field
         Filter List Button

   Description
         Submit Button for search.

   Additional Explanation
         This is the main submit button which should be clicked if you're using
         the fulltext search field or any other search field.


.. container:: table-row

   Field
         Start

   Description
         Choose a Start Date for the filter list.

   Additional Explanation
         Date starts on 0:00 o'clock. Clicking into the field opens a
         datepicker.


.. container:: table-row

   Field
         Stop

   Description
         Choose a Stop Date for the filter list.

   Additional Explanation
         Date starts on 0:00 o'clock. Clicking into the field opens a
         datepicker.Example: From 1.1.2012 up to 2.1.2012 filter emails of 24h.


.. container:: table-row

   Field
         Sender Name

   Description
         Search through the sender name field of the stored mail.

   Additional Explanation
         All fields are related to each other with OR.


.. container:: table-row

   Field
         Sender Email

   Description
         Search through the sender email field of the stored mail.

   Additional Explanation
         All fields are related to each other with OR.


.. container:: table-row

   Field
         Subject

   Description
         Search through the subjtect field of the stored mail.

   Additional Explanation
         All fields are related to each other with OR.


.. container:: table-row

   Field
         Deactivated Mails

   Description
         Show only activated or deactivated mails.

   Additional Explanation
         Deactivated mails could be interesting if you use Double-Opt-In e.g.


.. container:: table-row

   Field
         Additional Fields

   Description
         Clicking on the green Plus Symbol opens a list of all fields (e.g.
         firstname, lastname, email, etc...) from the form.

   Additional Explanation
         All fields are related to each other with OR.


.. ###### END~OF~TABLE ######


Export Area
~~~~~~~~~~~

Export Area gives you the possibility to export your mails in XLS or
CSV format.

|img-81|

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Field
         Field:

   Description
         Description:

   Additional Explanation
         Additional Explanation:


.. container:: table-row

   Field
         XLS Icon

   Description
         If you want to export the current list in XLS-Format, click the icon.

         XLS-Files can be opened with Microsoft Excel or Open Office (e.g.).

   Additional Explanation
         If you filter or sort the list before, the export will only export the
         filtered mails.

         See “Columns in Export File” if you want to change the export file
         columns.


.. container:: table-row

   Field
         CSV Icon

   Description
         If you want to export the current list in CSV-Format, click the icon.

         CSV-Files can be opened with Microsoft Excel or Open Office (e.g.).

   Additional Explanation
         If you filter or sort the list before, the export will only export the
         filtered mails.

         See “Columns in Export File” if you want to change the export file
         columns.


.. container:: table-row

   Field
         Columns in Export File

   Description
         This area shows the columns and the ordering of the rows in the
         export-file.Play around with drag and drop.

   Additional Explanation
         Change sorting: Drag and drop a line up or down

         Add row: Choose a line of the “Available Columns” and drop on “Columns
         in Export File”

         Remove row: Drag line and move to the “Available Columns”


.. container:: table-row

   Field
         Available Columns

   Description
         This area shows the available columns that can be used in the export
         file.

   Additional Explanation
         See Row before for an explanation.


.. ###### END~OF~TABLE ######

