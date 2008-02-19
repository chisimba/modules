<?php

$this->setLayoutTemplate('faq2_layout_tpl.php');

    $header = $this->getHTMLElement('htmlheading');
    $header->type = 4;
    $header->str =$objLanguage->languageText("faq2_sayitedit",'faq2');
    $header->str .="&nbsp;".$objLanguage->languageText("faq2_into",'faq2');;
    $header->str .="&nbsp;".$objLanguage->languageText("faq2_category",'faq2');
    $header->str .="&nbsp;&raquo;&nbsp;".$category[0]['categoryname'];
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
    //now set action depending on what we are editing
    //if translatio=1 then we are editing a translation
    //else the original faq
  
    if($translation==0)
    $action='editfaqentryconfirm';
    else
    $action='edittranslationconfirm';
   
    // Display form.
	$form = new form("edit",
		$this->uri(array(
	    	'module'=>'faq2',
	   		'action'=>$action,
			'id'=>$list[0]['id'],
                        'entryid'=>$list[0]['entryid'],
                        'catid'=>$category[0]['categoryid']
	)));
	$form->setDisplayType(1);


	

	$label = new label ($objLanguage->languageText("word_question"), 'input_question');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textarea("question", $list[0]['question'], 5, 80));

	$label = new label ($objLanguage->languageText("word_answer"), 'input_answer');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textarea("answer", $list[0]['answer'], 5, 80));

	
	
	$labellang=new label($objLanguage->languageText("mod_faq2_selectlanguage","faq2"),'');
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

  

        $form->addToForm('&nbsp;');
        $form->addToForm('&nbsp;');

	$button = new button("submit", $objLanguage->languageText("word_update"));
	$button->setToSubmit();

        $cancelButton =new button("submit", $objLanguage->languageText("word_cancel"));
        $cancelButton->setOnClick("window.location='".$this->uri(array('action'=>'view', 'catid'=>$categoryid))."';");

	$form->addToForm($button->show().' / '.$cancelButton->show());

	echo $form->show();
	
?>
