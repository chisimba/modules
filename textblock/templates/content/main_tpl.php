<?php

//View template for table: tbl_textblock

//Set up the button class to make the edit, add and delet icons
$objButtons = & $this->getObject('navbuttons', 'navigation');
$objDate =  & $this->getObject("datetime", "utilities");

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

//Set the content of the left side column
$leftSideColumn = $objLanguage->languageText("mod_textblock_leftside",'textblock');
// Add param array
$paramArray = array('action' => 'add');
// Create header
$this->objH =& $this->getObject('htmlheading', 'htmlelements');
//Heading <h1>
$this->objH->type=1; 

//Create an instance of the User object
$objUser =  $this->getObject("user", "security");
//You need to write your security here
if ($objUser->isAdmin()) {
	$allowAdmin = TRUE;
  	$this->objH->str=$objLanguage->languageText("mod_textblock_title",'textblock')."&nbsp;".$objButtons->linkedButton("add",$this->uri($paramArray));
 } else {
    $this->objH->str=$objLanguage->languageText("mod_textblock_title",'textblock');
    $allowAdmin = FALSE;
}
$rightSideColumn = $this->objH->show();

// Add Left column
$objBlocks = &$this->getObject('blocks','blocks');
$leftSideColumn .= $objBlocks->showBlock("text1", 'textblock')
  . $objBlocks->showBlock("text2", 'textblock')
  . $objBlocks->showBlock("text3", 'textblock')
  . $objBlocks->showBlock("text4", 'textblock')
  . $objBlocks->showBlock("text5", 'textblock')
  . $objBlocks->showBlock("text6", 'textblock');


$cssLayout->setLeftColumnContent($leftSideColumn);

//Create a table
$this->Table = $this->newObject('htmltable', 'htmlelements');
$this->Table->cellspacing="2";
$this->Table->cellpadding="2";
$this->Table->width="98%";
$this->Table->id="unpadded";
//Create the array for the table header
$tableRow=array();
$tableHd[]=$objLanguage->languageText('mod_textblock_blocktext','textblock');
$tableHd[]=$objLanguage->languageText('mod_textblock_blocktext','textblock');
$tableHd[]=$objLanguage->languageText('mod_textblock_datecreated','textblock');
$tableHd[]=$objLanguage->languageText('mod_textblock_createdby','textblock');
$tableHd[]=$objLanguage->languageText('mod_textblock_datemodified','textblock');
$tableHd[]=$objLanguage->languageText('mod_textblock_modifiedby','textblock');
if($allowAdmin){
$tableHd[]=$objLanguage->languageText('mod_textblock_action','textblock');
}
//Get the icon class and create an add, edit and delete instance
$objAddIcon = $this->newObject('geticon', 'htmlelements');
$objEditIcon = $this->newObject('geticon', 'htmlelements');
$objDelIcon = $this->newObject('geticon', 'htmlelements');
//Create the table header for display
$this->Table->addHeader($tableHd, "heading");

//Loop through and display the records
$rowcount = 0;
if (isset($ar)) {
    if (count($ar) > 0) {
        foreach ($ar as $line) {
            $oddOrEven = ($rowcount == 0) ? "odd" : "even";
            $tableRow[]=$line['title'];
            $tableRow[]=$line['blocktext'];
            if(!empty($line['datecreated'])){
                $tableRow[]=$objDate->formatDate($line['datecreated']);
            }else{
                $tableRow[]= '';
            }                      
		    if(!empty($line['creatorid'])){
            	$tableRow[]=$objUser->fullname($line['creatorid']);
		    }else{
                $tableRow[]= '';
            }
            if(!empty($line['datemodified'])){
                $tableRow[]=$objDate->formatDate($line['datemodified']);
            }else{
                $tableRow[]= '';
            }
            
            if(isset($line['modifierid'])){
            $tableRow[]=$objUser->fullname($line['modifierid']);
            } else{
            $tableRow[]='';
            }

            //The URL for the edit link
            $editLink=$this->uri(array('action' => 'edit',
              'id' =>$line['id']));
            $objEditIcon->alt=$this->objLanguage->languageText("mod_textblock_editalt",'textblock');
            $ed = $objEditIcon->getEditIcon($editLink);

            // The delete icon with link uses confirm delete utility
            $objDelIcon->setIcon("delete");
            $objDelIcon->alt=$this->objLanguage->languageText("mod_textblock_del",'textblock');
            $delLink = $this->uri(array(
              'action' => 'delete',
              'confirm' => 'yes',
              'id' => $line['id']));
            $objConfirm = & $this->newObject('confirm','utilities');
            $rep = array('ITEM', $line['id']);
            $objConfirm->setConfirm($objDelIcon->show(),
            $delLink,$this->objLanguage->code2Txt("mod_textblock_conf",'textblock', $rep));
            $conf = $objConfirm->show();
            if($allowAdmin){
            $tableRow[]=$ed."&nbsp;".$conf;
            }
            //Add the row to the table for output
            $this->Table->addRow($tableRow, $oddOrEven);
            $tableRow=array(); // clear it out
            // Set rowcount for bitwise determination of odd or even
            $rowcount = ($rowcount == 0) ? 1 : 0;

        }
    }
}
//Add the table to the centered layer
$rightSideColumn .= $this->Table->show();
// Create add link
$objAddLink = & $this->getObject('link', 'htmlelements');
$objAddLink->link($this->uri(array('action' => 'add')));
$objAddLink->link = $this->objLanguage->languageText('mod_textblock_addnew','textblock');
if($allowAdmin){
// Add the add link to the centered layer
$rightSideColumn .= '<p>'.$objAddLink->show().'</p>';
}
// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();

?>
