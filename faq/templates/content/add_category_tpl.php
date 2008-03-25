<?php
// Show the heading.
$objHeading = $this->newObject('htmlheading','htmlelements');
$objHeading->type=1;
$objHeading->str =$objLanguage->languageText("mod_faq_addcategory","faq");
echo $objHeading->show();
// Load the classes.
$this->loadClass("form","htmlelements");
$this->loadClass("textinput","htmlelements");
$this->loadClass("button","htmlelements");
// Create the form.
$form = new form("createcategory", $this->uri(array('action'=>'addcategoryconfirm')));

$textInput = new textinput("category", NULL);
$textInput->size = 40;

$form->setDisplayType(1);
$form->addToForm($textInput->show());
$form->addToForm("&nbsp;");
$button = new button("submit", $objLanguage->languageText("word_save"));
$button->setToSubmit();

$cancelButton = new button("submit", $objLanguage->languageText('word_cancel'));
$cancelButton->setOnClick("window.location='".$this->uri(NULL)."';");

$form->addToForm($button->show().' / '.$cancelButton->show());
$form->addRule('category', 'Please enter the name of the category', 'required');
// Show the form.
echo $form->show();

?>