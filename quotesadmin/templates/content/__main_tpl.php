<?php

//View template for table: tbl_guestbook
//Note that you will probably need to edit this to make it actually work


//Set up the button class to make the edit, add and delet icons
$objButtons = & $this->getObject('navbuttons', 'navigation');

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(1);

//Create a table
$this->Table = $this->newObject('htmltable', 'htmlelements');
$this->Table->cellspacing="2";
$this->Table->width="80%";
$this->Table->attributes="align=\"center\"";
//Create the array for the table header
$tableRow = array();
$tableHd[] = "id";
$tableHd[] = $objLanguage->languageText("mod_quotes_quote", 'quotes');
$tableHd[] = $this->objLanguage->languageText("mod_quotes_whosaid",'quotes');
$allowAdmin = True; //You need to write your security here
$tableHd[]="&nbsp;";


//Get the icon class and create an add, edit and delete instance
$objAddIcon = $this->newObject('geticon', 'htmlelements');
$objEditIcon = $this->newObject('geticon', 'htmlelements');
$objDelIcon = $this->newObject('geticon', 'htmlelements');
//Create the table header for display
$this->Table->addHeader($tableHd, "heading");

//Add the navigation
$this->Table->startRow();
$this->Table->addCell($nav, NULL, "top",  NULL,  NULL, " colspan=\"2\"");
$this->Table->endRow();

//Loop through and display the records
$rowcount = 0;
if (isset($ar)) {
    if (count($ar) > 0) {
        foreach ($ar as $line) {
            $oddOrEven = ($rowcount == 0) ? "odd" : "even";
            $tableRow[]=$line['quote'];
            $tableRow[]=$line['whosaid'];
            // The delete icon with link uses confirm delete utility
            $objDelIcon->setIcon("delete");
            $objDelIcon->alt=$this->objLanguage->languageText("mod_guestbookadmin_delalt");
            $delLink = $this->uri(array(
              'action' => 'delete',
              'confirm' => 'yes',
              'id' => $line['id']), 'guestbookadmin');
            $objConfirm = & $this->newObject('confirm','utilities');
            $rep = array('ITEM', $line['id']);
            $objConfirm->setConfirm($objDelIcon->show(),
            $delLink,$this->objLanguage->code2Txt("mod_guestbook_confirm", $rep));
            $conf = $objConfirm->show();
            $tableRow[]=$conf;            //Add the row to the table for output
            $this->Table->addRow($tableRow, $oddOrEven);
            $tableRow=array(); // clear it out
            // Set rowcount for bitwise determination of odd or even
            $rowcount = ($rowcount == 0) ? 1 : 0;

        }
    }
}
//Add the navigation
$this->Table->startRow();
$this->Table->addCell($nav, NULL, "top",  NULL,  NULL, " colspan=\"2\"");
$this->Table->endRow();
//Add the table to the centered layer
$rightSideColumn .= $this->Table->show();

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();

?>
