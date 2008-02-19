<?php
$this->setLayoutTemplate('faq2_layout_tpl.php');



// Show the heading.
$objHeading =& $this->getObject('htmlheading','htmlelements');
$objHeading->type=4;
$objHeading->str =$objLanguage->languageText("mod_faq2_editcategory","faq2");
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

$form = new form("edit",
		$this->uri(array(
	    	'module'=>'faq2',
	   		'action'=>'editcategoryconfirm',
			'id'=>$list[0]['entryid'],
                        'catid'=>$list[0]['categoryid']
	)));
	$form->setDisplayType(1);

$textInput = new textinput("category", $list[0]['categoryname']);
$textInput->size = 40;


$form->setDisplayType(1);
$form->addToForm("<b>" . $labelcategory->show() . ":</b> &nbsp;<br>".$textInput->show());

//checkbox to hold is default language
	if($list[0]['isdefaultlang']=='Y')
	$checkbox=new checkbox('isdefaultlang','', TRUE);
	else
	$checkbox=new checkbox('isdefaultlang','', FALSE);
	
	$labellang=new label($objLanguage->languageText("mod_faq2_selectlanguage","faq2"),'');
	//$labelisdefaultlang=new label($objLanguage->languageText("mod_faq2_isdefaultlanguage","faq2"),'');
	$labelselectlicense=new label($objLanguage->languageText("mod_creativecommons_selectalicense"),'');
	
	//$language =& $this->newObject('language','language');
         $languageCodes = & $this->newObject('languagecode','language');
        $languageDropdown = new dropdown('language');
        // Sort Associative Array by Language, not ISO Code
        $languageList = $languageCodes->iso_639_2_tags->codes;
    
        asort($languageList);

        foreach ($languageCodes->iso_639_2_tags->codes as $key => $value) {
        $languageDropdown->addOption($key, $value);
        }
        $languageDropdown->setSelected($list[0]['language']);
    

        $form->addToForm("<b>".$labellang->show().":<b><br>" . $languageDropdown->show());
	
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
  
