<?php
$this->setLayoutTemplate('faq2_layout_tpl.php');



// Show the heading.
$objHeading =& $this->getObject('htmlheading','htmlelements');
$objHeading->type=4;
$objHeading->str =$objLanguage->languageText("mod_faq2_addcategory","faq2");
echo $objHeading->show();
//show css
echo $display;
// Load the classes.
$this->loadClass("form","htmlelements");
$this->loadClass("label","htmlelements");
$this->loadClass("textinput","htmlelements");
$this->loadClass("button","htmlelements");
$this->loadClass("dropdown","htmlelements");
$this->loadClass("checkbox","htmlelements");
//create labels
$labelcategory=new label($objLanguage->languageText("faq2_category","faq2"),'');
$labellang=new label($objLanguage->languageText("mod_faq2_selectlanguage","faq2"),'');
$labelisdefaultlang=new label($objLanguage->languageText("mod_faq2_isdefaultlanguage","faq2"),'');
$labelselectlicense=new label($objLanguage->languageText("mod_creativecommons_selectalicense"),'');

//languages dropdown container
$dropdown=new dropdown("language");
//checkbox to hold is default language
$checkbox=new checkbox('isdefaultlang','', TRUE);

// Create the form.
$form = new form("createcategory", $this->uri(array('action'=>'addCategoryConfirm')));

$textInput = new textinput("category", NULL);
$textInput->size = 40;

$form->setDisplayType(1);
$form->addToForm("<b>" . $labelcategory->show() . ":</b> &nbsp;<br>".$textInput->show());

//$language =& $this->newObject('language','language');
         $languageCodes = & $this->newObject('languagecode','language');
        $languageDropdown = new dropdown('language');
        // Sort Associative Array by Language, not ISO Code
        $languageList = $languageCodes->iso_639_2_tags->codes;
    
        asort($languageList);
    
        foreach ($languageList as $key => $value) {
        $languageDropdown->addOption($key, $value);
        }
    
         if ($mode == 'fix') {
        $languageDropdown->setSelected($details['language']);
        } else {
        $languageDropdown->setSelected($languageCodes->getISO($this->objLanguage->currentLanguage()));
         }
    
        
        $form->addToForm("<b>" . $languageDropdown->show() . ":&nbsp".$labelisdefaultlang->show().":&nbsp</b> ".$checkbox->show());

$form->addToForm("<b>" . $labelselectlicense->show() . ":</b> &nbsp;<br>".$this->objCreativecommons->show());


$form->addToForm("&nbsp;");
$button = new button("submit", $objLanguage->languageText("word_save"));
$button->setToSubmit();

$cancelButton = new button("submit", $objLanguage->languageText('word_cancel'));
$cancelButton->setOnClick("window.location='".$this->uri(NULL)."';");

$form->addToForm($button->show().' / '.$cancelButton->show());
// Show the form.
echo $form->show();
?>
  
