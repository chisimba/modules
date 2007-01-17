<?php

    $header = $this->getHTMLElement('htmlheading');
    $header->type = 1;
    $header->str =$objLanguage->languageText("faq_sayitadd");

    echo $header->show();

    // Load classes.
	$this->loadHTMLElement("form");
	$this->loadHTMLElement('textinput');
	$this->loadHTMLElement("textarea");
	$this->loadHTMLElement("button");
    $this->loadHTMLElement("label");
    // Display form.
	$form = new form("add",
		$this->uri(array(
	    	'module'=>'faq',
	   		'action'=>'addConfirm',
			'category'=>$categoryId
	)));
	$form->setDisplayType(1);

   $label = new label (($objLanguage->languageText("word_index")), 'input_index');

	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textinput("index", $this->objDbFaqEntries->getNextIndex($contextId, $categoryId)));

    $label = new label ($objLanguage->languageText("word_question"), 'input_question');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textarea("question", NULL, 5, 80));

    $label = new label ($objLanguage->languageText("word_answer"), 'input_answer');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textarea("answer", NULL, 5, 80));

    $label = new label ($objLanguage->languageText("faq_category","faq"), 'input_category');
	$form->addToForm("<b>" . $label->show() . ":</b>");

    $dropdown = new dropdown('category');
	foreach ($categories as $item) {
		$dropdown->addOption($item["id"],$item["categoryid"]);
	}

	$dropdown->setSelected($categoryId);
	$form->addToForm($dropdown);

    $form->addToForm('&nbsp;');
    $form->addToForm('&nbsp;');

	$button = new button("submit", $objLanguage->languageText("word_add"));
	$button->setToSubmit();

    $cancelButton =new button("submit", $objLanguage->languageText("word_cancel"));
    $cancelButton->setOnClick("window.location='".$this->uri(array('action'=>'view', 'category'=>$categoryId))."';");

	$form->addToForm($button->show().' / '.$cancelButton->show());

	echo $form->show();
?>
