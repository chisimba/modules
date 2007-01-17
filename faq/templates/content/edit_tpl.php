<?php
	$header = $this->getHTMLElement('htmlheading');
    $header->type = 1;
    $header->str =$objLanguage->languageText("faq_sayitedit","faq");

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
	   		'action'=>'editConfirm',
			'category'=>$categoryId,
			'id'=>$list[0]["id"]
	)));
	$form->setDisplayType(1);

    $label = new label ($objLanguage->languageText("word_index"), 'input_index');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textinput("index", $list[0]['_index']));

	$label = new label ($objLanguage->languageText("word_question"), 'input_question');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textarea("question", $list[0]["question"], 5, 80));

	$label = new label ($objLanguage->languageText("word_answer"), 'input_answer');
	$form->addToForm("<b>" . $label->show() . ":</b>");
	$form->addToForm(new textarea("answer", $list[0]["answer"], 5, 80));

    $label = new label ($objLanguage->languageText("faq_category","faq"), 'input_category');
	$form->addToForm("<b>" . $label->show() . ":</b>");

    $dropdown = new dropdown('category');
	foreach ($categories as $item) {
		$dropdown->addOption($item["id"],$item["categoryid"]);
	}

	$dropdown->setSelected($list[0]["categoryId"]);

	$form->addToForm($dropdown);

    $form->addToForm('&nbsp;');
    $form->addToForm('&nbsp;');


	$button = new button("submit", $objLanguage->languageText("word_save"));
	$button->setToSubmit();

    $cancelButton =new button("submit", $objLanguage->languageText("word_cancel","faq"));
    $cancelButton->setOnClick("window.location='".$this->uri(array('action'=>'view', 'category'=>$categoryId))."';");

	$form->addToForm($button->show().' / '.$cancelButton->show());
	echo $form->show();
?>
