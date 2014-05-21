.. include:: Images.txt
.. include:: ../../Includes.txt

Powermail\_Frontend
^^^^^^^^^^^^^^^^^^^


Introduction
""""""""""""

powermail\_frontend gives you the possibility to show the stored mails
again in the frontend. With this plugin (Pi2), it's possible to create
a small guestbook or database listing. In addition some export methods
are included (XLS, CSV, RSS) or logged in FE\_Users can change the
values again.

|img-85|

Plugin Settings
"""""""""""""""

|img-86|

.. t3-field-list-table::
 :header-rows: 1

 - :Tab:
      Tab
   :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation

 - :Tab:
      Main Settings
   :Field:
      Choose your view
   :Description:
      Choose a view
   :Explanation:
      List, Detail, Edit

 - :Tab:
      Main Settings
   :Field:
      Choose your view
   :Description:
      Choose a view
   :Explanation:
      List, Detail, Edit



================     ====================== =================
Tab           Field               Description            Explanation
================     ====================== =================
Main Settings        Choose your view    Choose a view.         List, Detail, Edit
Main Settings     Choose a form       Choose an existing form.  Only mails related to this form are shown in the frontend.
Main Settings    Select a page with mails  Select mail storage page.    Only mails which are stored in the given page are shown in the frontent (optional setting)
Listview         Choose Fields to show     What field should be listed in the list view?        Let the selector empty if you want to see all form values.
Listview      Export Formats       Add links to different export methods by adding some.    XLS, CSV or RSS feed is possible in powermail\_frontend.
Listview      Show entries...      If you want to show only mails within a timeperiod, add some seconds.        If you want to show the mails of the last 24h add “86400”
Listview      Show max. X entries...      Limit for mail output.     Add a number if you want to show only X entries.
Listview      Page with Plugin for list view...   Select the page with the list plugin.    This is needed if the plugin shows the edit or single view and it should link you back to the list view. Let this field empty means list view is on current page.
Listview      Own entries         Show only my mails.

   Explanation
         If this option is checked, only mails from the current logged in
         FE\_User are shown in the frontend.

   Tab
         Listview


.. container:: table-row

   Field
         Choose Fields to show

   Description
         What field should be listed in the detail view?

   Explanation
         Let the selector empty if you want to see all form values.

   Tab
         Detailview


.. container:: table-row

   Field
         Page with Plugin for detail view

   Description
         Select the page with the detail plugin.

   Explanation
         This is needed if the plugin shows the list view and it should link
         you to the detail view. Let this field empty means detail view is on
         current page.

   Tab
         Detailview


.. container:: table-row

   Field
         Add searchfield

   Description
         Add some search fields above the list.

   Explanation
         Select a single field or choose [Fulltext Search] for an overall
         search

   Tab
         Searchsettings


.. container:: table-row

   Field
         Add ABC filter

   Description
         Add ABC filter list in frontend.

   Explanation
         Select a field with a leading letter to filter for it. Firstname
         means: When a user clicks on A, all mails with a beginning A in the
         firstname are shown (Alex, Andreas, Agnes, etc...)

   Tab
         Searchsettings


.. container:: table-row

   Field
         Choose Fields to edit

   Description
         What fields should be editable?

   Explanation
         Let the selector empty if you want to edit all fields.

   Tab
         Editview


.. container:: table-row

   Field
         Choose one or more Frontend-Users with permissions to change

   Description
         Choose a frontend user who is able to edit a mail.

   Explanation
         Value can be one or more static FE\_Users or the Creator of a mail
         [Owner]. You can select a group in addition (see next row).

   Tab
         Editview


.. container:: table-row

   Field
         Choose one or more Frontend-Groups with permissions to change

   Description
         Choose frontend users of a group which are able to edit a mail.

   Explanation
         Value can be one or more static FE\_User Groups or the Creator Group
         of a mail [Owner]. You can select some single FE\_Users in addition
         (see row before).

   Tab
         Editview


.. container:: table-row

   Field
         Page with Plugin for edit view

   Description
         Select the page with the edit plugin.

   Explanation
         This is needed if the plugin shows the list view and it should link
         you to the edit view. Let this field empty means edit view is on
         current page.

   Tab
         Editview


.. ###### END~OF~TABLE ######

