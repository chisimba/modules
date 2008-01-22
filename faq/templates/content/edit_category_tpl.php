<?php
// Show the heading.
$objHeading =& $this->getObject('htmlheading','htmlelements');
$objHeading->type=1;
$objHeading->str =$objLanguage->languageText("mod_faq_editcategory","faq").' : <em>'.$list[0]['categoryname'].'</em>';
echo $objHeading->show();
// Load the classes.
$this->loadClass("form","htmlelements");
$this->loadClass("textinput","htmlelements");
$this->loadClass("button","htmlelements");
// Create the form.
$form = new form("createcategory", 
$this->uri(array(
 	'module'=>'faq',
    'action'=>'editCategoryConfirm',
    'id'=>$list[0]['id']
))	
);
$form->setDisplayType(1);

$textInput = new textinput("category",$list[0]['categoryname']);
$textInput->size = 40;

$form->addToForm($textInput->show());
$form->addToForm("&nbsp;");
$button = new button("submit", $objLanguage->languageText("word_save"));
$button->setToSubmit();

$cancelButton = new button("submit", $objLanguage->languageText('word_cancel'));
$cancelButton->setOnClick("window.location='".$this->uri(NULL)."';");

$form->addToForm($button->show().' / '.$cancelButton->show());
// Show the form.
echo $form->show();
?>
