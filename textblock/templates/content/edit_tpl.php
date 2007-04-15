<?php

//Set up the form action
$paramArray=array(
'action'=>'save',
'mode'=>$mode);
$formAction=$this->uri($paramArray);

//Load the form class 
$this->loadClass('form','htmlelements');
//Load the textinput class 
$this->loadClass('textinput','htmlelements');
//Load the textarea class 
$this->loadClass('textarea','htmlelements');
//Load the label class
$this->loadClass('label','htmlelements');
//Create and instance of the form class
$objForm = new form('tbl_quotes');
//Set the action for the form to the uri with paramArray
$objForm->setAction($formAction);
//Set the displayType to 3 for freeform
$objForm->displayType=3;
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);



//Set the content of the left side column
$leftSideColumn = "Replace this with what you want in the leftside column";
// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);



//Retrieve the data
if (isset($ar)) {
    $id = $ar['id'];
    $title = $ar['title'];
    $blockid = $ar['blockid'];
    $blocktext = $ar['blocktext'];
    $dateCreated = $ar['datecreated'];
    $creatorId = $ar['creatorid'];
    $dateModified = $ar['datemodified'];
    $modifierId = $ar['modifierid'];
}

//------------------------------------------------------------------------
//Create an element for the hidden text input
$objElement = new textinput("id");
//Set the value to the primary keyid
if (isset($id)) {
    $objElement->setValue($id);
}
//Set the field type to hidden for the primary key
$objElement->fldType="hidden";
//Add the hidden PK field to the form
$objForm->addToForm($objElement->show());
//------------------------------------------------------------------------
//Create an element for the input of blockid
$objElement = new textinput ("blockid");
//Set the value of the element to $blockid
if (isset($blockid)) {
    $objElement->setValue($blockid);
}
// Create label for blocktext
$wsiLabel = new label($this->objLanguage->languageText('mod_textblock_field_blockid','textblock'), "input_blockid");
//Add the $blocktext element to the form
$objForm->addToForm($wsiLabel->show()."<br />".$objElement->show()."<br /><br />");
//------------------------------------------------------------------------
//Create an element for the input of title
$objElement = new textinput ("title");
//Set the value of the element to $title
if (isset($title)) {
    $objElement->setValue($title);
}
// Create label for blocktext
$wsiLabel = new label($this->objLanguage->languageText('mod_textblock_field_title','textblock'), "input_title");
//Add the $blocktext element to the form
$objForm->addToForm($wsiLabel->show()."<br />".$objElement->show()."<br /><br />");
//------------------------------------------------------------------------
//die(htmlspecialchars($blocktext));

//Create an element for the input of block text
$objElement = new textarea ("blocktext");
//Set the value of the element to $title
if (isset($blocktext)) {
    $objElement->setContent(htmlspecialchars($blocktext));
}
//Create label for the input of quote
$quoteLabel = new label($this->objLanguage->languageText('mod_textblock_field_blocktext','textblock'), "input_blocktext");
//Add the $quote element to the form
$objForm->addToForm($quoteLabel->show()."<br />".$objElement->show()."<br /><br />");
//------------------------------------------------------------------------
// Create an instance of the button object
$this->loadClass('button', 'htmlelements');
// Create a submit button
$objElement = new button('submit');	
// Set the button type to submit
$objElement->setToSubmit();	
// Use the language object to add the word save
$objElement->setValue(' '.$this->objLanguage->languageText("word_save").' ');
//Create cancel button
$objCancel = new button('cancel');
$objCancel->setOnClick("window.location='".$this->uri(NULL)."';");
$objCancel->setValue(' ' . $this->objLanguage->languageText("mod_textblock_cancel",'textblock') . ' ');
// Add the buttons to the form
$objForm->addToForm('<br/>'.$objElement->show()."&nbsp;".$objCancel->show());



//Add the heading to the layer
$this->objH =& $this->getObject('htmlheading', 'htmlelements');
$this->objH->type=1; //Heading <h1>
if($mode=='edit'){
  $this->objH->str=$objLanguage->languageText("mod_textblock_edittitle",'textblock');
 } else {
       $this->objH->str=$objLanguage->languageText("mod_textblock_addtitle",'textblock');
      }
$rightSideColumn = $this->objH->show();

$rightSideColumn .= $objForm->show();
// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();

?>
