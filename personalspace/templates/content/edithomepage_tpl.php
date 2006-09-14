<?php
	echo "<h3>".$objLanguage->languageText("phrase_myhomepage")."</h3>";
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("button","htmlelements");
    // Display form.
	$form = new form("edit", 
		$this->uri(array(
	    	'module'=>'personalspace',
	   		'action'=>'edithomepageconfirm',
			'userId'=>$userId
	)));
	$htmlarea = $this->getObject("htmlarea","htmlelements");
	$htmlarea->init("contents", $contents, 20, 100);	
	$form->addToForm($htmlarea);
	$button = new button("submit", $objLanguage->languageText("word_save"));
	$button->setToSubmit();
	$form->addToForm($button);
	echo $form->show();
?>