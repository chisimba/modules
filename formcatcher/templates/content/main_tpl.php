<?php
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(3);

// Add the heading to the content of the middle column
$objH =& $this->getObject('htmlheading', 'htmlelements');
$objH->type=3;
$objH->str=$objLanguage->languageText("mod_formcatcher_name", "formcatcher");
$leftSideColumn = $objH->show();
//Set the content of the left side column
$leftSideColumn .= $objLanguage->languageText("mod_formcatcher_leftcolinfo", "formcatcher");





// Add the heading to the content
$objH =& $this->getObject('htmlheading', 'htmlelements');
$objH->type=3; //Heading <h3>
$objH->str=$objLanguage->languageText("mod_formcatcher_availfms", "formcatcher");
$middleColumn = $objH->show();

$saveMsg = $this->getParam('message', NULL);
if ($saveMsg !== NULL) {
    $middleColumn .= "<span class=\"confirm\">"
       . $saveMsg . "</span>";
}

//Set up the button class to make the edit, add and delete icons
$objButtons = & $this->getObject('navbuttons', 'navigation');
//Create a table
$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->cellspacing="2";
$myTable->width="98%";
$myTable->attributes="align=\"center\"";


//Get the icon class and create an add, edit and delete instance
$objEditIcon = $this->newObject('geticon', 'htmlelements');
$objDelIcon = $this->newObject('geticon', 'htmlelements');

//Create the table header for display
$myTable->startHeaderRow();
$myTable->addHeaderCell($objLanguage->languageText("word_title", "formcatcher"));
$myTable->addHeaderCell($objLanguage->languageText("word_description", "formcatcher"));
$myTable->addHeaderCell($objLanguage->languageText("word_file", "formcatcher"));
$myTable->addHeaderCell($objLanguage->languageText("word_data", "formcatcher"));

//Add the icon for uploading a new form
if( $this->isValid('addform') ) {
    $paramArray = array('action' => 'addform');
    $myTable->addHeaderCell($objButtons->linkedButton("add", $this->uri($paramArray)));
} else {
    $myTable->addHeaderCell("&nbsp;");
}

$myTable->endHeaderRow();

$objDlIcon = $this->newObject('geticon', 'htmlelements');
$objDlIcon->setIcon('download');

//Loop through and display the records
$rowcount = 0;
//Default to no data found
$arFound=FALSE;
if (isset($ar)) {
    if (count($ar) > 0) {
        //Set a flag for data found
        $arFound = TRUE;
        foreach ($ar as $line) {
            $oddOrEven = ($rowcount == 0) ? "odd" : "even";
            $link = $line['link'];
            //Add data to the table
            $myTable->startRow();
            $myTable->addCell($line['title'], $oddOrEven);
            $myTable->addCell($line['description'], $oddOrEven);
            
            $filename = $this->objFile->getFileName($line['filename']);
            //Add file with link to open form
            $openLink = $this->uri(array(
              'action' => 'view',
              'id' => $line['id'],
              'filename' => $line['filename']));
            $objLn = $this->getObject('link', 'htmlelements');
            $objLn->href = $openLink;
            $objLn->link = $filename;
            $myTable->addCell($objLn->show(), $oddOrEven);

            //Check if there is a data file and link to it
            $objPath = & $this->getObject('formuploader');
            $fullFile = $objPath->getUploadPath()
              . $line['filename'] . ".dat";
            if (file_exists($fullFile)) {
                // Context Code
                $objContext =& $this->getObject('dbcontext', 'context');
 		        $contextCode = $objContext->getContextCode();
                $objLn->href = "usrfiles/formcatcher/" . $contextCode
                  . "/" . $line['filename'].".dat";
                $objLn->alt = $line['filename'].".dat";
                $objLn->link = $objDlIcon->show();
                $myTable->addCell($objLn->show(), $oddOrEven);
            } else {
                $myTable->addCell("&nbsp;", $oddOrEven);
            }

            if( $this->isValid('edit') ) {
                //The URL for the edit link if they have permissions
                $editLink=$this->uri(array(
                  'action' => 'edit',
                  'mode' => 'edit',
                  'id' =>$line['id']));
                $objEditIcon->alt=$this->objLanguage->languageText("mod_formcatcher_editalt", "formcatcher");
                $ed = $objEditIcon->getEditIcon($editLink);
            } else {
                $ed = "&nbsp;";
            }
            // Show add icon only if a valid action
            if( $this->isValid('delete') ) {
                // The delete icon with link uses confirm delete utility
                $objDelIcon->setIcon("delete");
                $objDelIcon->alt=$this->objLanguage->languageText("mod_formcatcher_delalt", "formcatcher");
                $delLink = $this->uri(array(
                  'action' => 'delete',
                  'confirm' => 'yes',
                  'id' => $line['id'],
                  'filename' => $line['filename']));
                $objConfirm = & $this->newObject('confirm','utilities');
                $rep = array('ITEM' => $line['title']);
                $objConfirm->setConfirm($objDelIcon->show(),
                $delLink,$this->objLanguage->code2Txt("mod_formcatcher_confirm", "formcatcher", $rep));
                $conf = $objConfirm->show();
            } else {
                $conf = "&nbsp;";
            }
            $myTable->addCell($ed . " " . $conf, $oddOrEven);
            $myTable->endRow();
            $rowcount = ($rowcount == 0) ? 1 : 0;
        }
    }
}

//If there are no uploaded forms
if ($arFound !== TRUE) {
    $myTable->startRow();
    $myTable->addCell("<div class=\"noRecordsMessage\">"
       . $objLanguage->languageText("mod_formcatcher_errnoforms", "formcatcher")
       ."</div>", NULL, NULL, NULL, "odd", "colspan=\"4\"");
    $myTable->endRow();
}

//Add the table to the output layer
$middleColumn .= $myTable->show();





//------------------- RENDER IT OUT -------------------------

// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add middle Column
$cssLayout->setMiddleColumnContent($middleColumn);

// Add Right Column
$cssLayout->setRightColumnContent($objLanguage->languageText("mod_formcatcher_defaultright", "formcatcher"));

//Output the content to the page
echo $cssLayout->show();
?>