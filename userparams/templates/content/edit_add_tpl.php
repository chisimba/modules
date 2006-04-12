<?php
if ($action == 'edit') {
    //Get the variables from the array, $ar
    $id=$ar['id'];
    $pname=$ar['pname'];
    $pvalue=$ar['pvalue'];
    //Load and create the form class
    $formAction = $this->uri(
      array('action' => 'save', 
      'mode' => 'edit'));
    $rep = array('PARAM' => $pname);
    $heading=$this->objLanguage->code2Txt("mod_userparams_edit", $rep);
} else {
    //define variables to null to prevent errors
    $id="";
    $pname="";
    $pvalue="";
    //Load and create the form class
    $formAction = $this->uri(
      array('action' => 'save', 
      'mode' => 'add'));
    $heading=$this->objLanguage->languageText("mod_userparams_add");
}


//Create the page heading
$objHd = $this->newObject('htmlheading', 'htmlelements');
$objHd->str = $heading;
echo $objHd->show();


//Load the form elements that I need
$this->loadClass('textinput','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');


//Start the form
$objForm = new form('userparams');
$objForm->setAction($formAction);
$objForm->displayType=3; //free form, not using tables

//Create the table in the form
$objTable = $this->newObject('htmltable', 'htmlelements');

//Add a row for the pname
$objTable->startRow();
//Add hidden field for id
$idFld = "";
if ($action == 'edit') {
    $objElement = new textinput('id');
    $objElement->setValue($id);
    $objElement->fldType = "hidden";
    $idFld = $objElement->show();
}

$pnameLabel = new label ($idFld.$this->objLanguage->languageText("mod_userparams_pname"), 'input_Object');
$objTable->addCell($pnameLabel->show(), NULL, "top", "left");

//Create an instance of dropdown and use it here
$objDd = $this->newObject("dropdown", "htmlelements");
$objDd->name = 'pname';
if($action=='add'){
$objDd->addOption("","Choose one");
foreach ($arUp as $arUps){
        $userId = $this->objUser->userId();
        $pnames = $arUps['pname'];
        if(!$this->objDbUserparams->checkIfSet($pnames, $userId)){
          $objDd->addOption($pnames, $pnames);
         }
      } 
  }else {      
  $objDd->addOption($pname, $pname);
        }
$objTable->addCell($objDd->show());
//End the table row
$objTable->endRow();


//Add a value for pvalue
$objTable->startRow();

$pvalueLabel = new label ($this->objLanguage->languageText("mod_userparams_pvalue"), 'input_pvalue');
$objTable->addCell($pvalueLabel->show(), NULL, "top", "left");
//Input for the pvalue
$objElement = new textinput('pvalue');
$objElement->setValue($pvalue);
$objElement->size="60";
$objTable->addCell($objElement->show(), NULL, "top", "left");
//End the table row
$objTable->endRow();

//The save button
$objTable->startRow();
$objElement = new button('submit');	
$objElement->setToSubmit();	
$objElement->setValue(' '.$this->objLanguage->languageText("word_save").' ');

$cancelButton = new button('cancel');	
$cancelButton->setOnClick("window.location='".$this->uri(NULL)."'");	
$cancelButton->setValue(' '.$this->objLanguage->languageText("word_cancel").' ');

$objTable->addCell($objElement->show().' / '.$cancelButton->show(), NULL, "top", "left", NULL, "colspan=\"2\"");
//End the table row
$objTable->endRow();

//Add the table to the form
$objForm->addToForm($objTable->show());

//Render the output
echo $objForm->show();

?>
