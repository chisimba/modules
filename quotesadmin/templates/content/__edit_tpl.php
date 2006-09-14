<?php

/* 
Set up the form processor
Note that this is only a basic edit form. You will want to
edit this form and change it to be more along the lines of what
you want. Especially note inputs that should not be there, etc.
You can also change the layout of the form.*/

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
//Create and instance of the form class
$objForm = new form('tbl_guestbook');
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
    $creatorId = $ar['creatorId'];
    $quote = $ar['quote'];
    $whosaidit = $ar['whosaidit'];
    $dateCreated = $ar['dateCreated'];
    $modifierId = $ar['modifierId'];
    $dateModified = $ar['dateModified'];
}


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


//Create an element for the input of comment
$objElement = new textarea ("quote");
//Set the value of the element to $comment
if (isset($quote)) {
    $objElement->setContent($quote);
}
//Add the $comment element to the form
$objForm->addToForm("comment<br />".$objElement->show()."<br /><br />");

// Create an instance of the button object
$this->loadClass('button', 'htmlelements');
// Create a submit button
$objElement = new button('submit');	
// Set the button type to submit
$objElement->setToSubmit();	
// Use the language object to add the word save
$objElement->setValue(' '.$this->objLanguage->languageText("word_save").' ');
// Add the button to the form
$objForm->addToForm('<br/>'.$objElement->show());
//Add the heading to the layer
$this->objH =& $this->getObject('htmlheading', 'htmlelements');
$this->objH->type=3; //Heading <h3>
$this->objH->str=$objLanguage->languageText("Replace_this_with_your_title_code");
$rightSideColumn = $this->objH->show();

$rightSideColumn .= $objForm->show();
// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();

?>