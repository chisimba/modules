<?php

$this->setLayoutTemplate('faq2_layout_tpl.php');



    $header = $this->getHTMLElement('htmlheading');
    $header->type = 4;
    $header->str =$objLanguage->languageText("faq2_addnewentry",'faq2');
    $header->str .="&nbsp;".$objLanguage->languageText("faq2_into",'faq2');;
    $header->str .="&nbsp;".$objLanguage->languageText("faq2_category",'faq2');
    $header->str .="&nbsp;&raquo;&nbsp;".$categoryname;
    echo $header->show();
echo $display;
    // Load classes.
	$this->loadHTMLElement("form");
	$this->loadHTMLElement('textinput');
	$this->loadHTMLElement("textarea");
	$this->loadHTMLElement("button");
	$this->loadHTMLElement("checkbox");
	$this->loadHTMLElement("dropdown");
    $this->loadHTMLElement("label");
    // Display form.
	$form = new form("add",
		$this->uri(array(
	    	'module'=>'faq',
	   		'action'=>'addConfirm',
			'categoryid'=>$categoryid
	)));
	$form->setDisplayType(1);
//

        /*$label = new label (($objLanguage->languageText("word_index")), 'input_index');
	$form->addToForm(new textinput("categoryid",$categoryid));
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textinput("entryorder", $this->objDbFaqEntries->getNextIndex($categoryid)));
        */
	$label = new label ($objLanguage->languageText("word_question"), 'input_question');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textarea("question", NULL, 5, 80));

	$label = new label ($objLanguage->languageText("word_answer"), 'input_answer');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textarea("answer", NULL, 5, 80));

	
	$labellang=new label($objLanguage->languageText("mod_faq2_selectlanguage","faq2"),'');
	$labelisdefaultlang=new label($objLanguage->languageText("mod_faq2_isdefaultlanguage","faq2"),'');
	$labelselectlicense=new label($objLanguage->languageText("mod_creativecommons_selectalicense"),'');
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
    
        
        $form->addToForm("<b>" . $languageDropdown->show());
	
	$form->addToForm("<b>" . $labelselectlicense->show() . ":</b> &nbsp;<br>".$this->objCreativecommons->show());

  

    $form->addToForm('&nbsp;');
    $form->addToForm('&nbsp;');

	$button = new button("submit", $objLanguage->languageText("word_add"));
	$button->setToSubmit();

    $cancelButton =new button("submit", $objLanguage->languageText("word_cancel"));
    $cancelButton->setOnClick("window.location='".$this->uri(array('action'=>'view', 'catid'=>$categoryid))."';");

	$form->addToForm($button->show().' / '.$cancelButton->show());

	echo $form->show();
	
?>
