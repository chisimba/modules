<?php
    echo "<h1>" . $objLanguage->languageText('mod_homepage_heading', 'homepage') /*. " " . $this->objUser->fullName()*/ . "</h1>";
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
	$htmlarea->init("contents", $contents, 500, 100);
	$htmlarea->width = '500px';	
	$form->addToForm($htmlarea->showFCKEditor());
	$button = new button("submit", $objLanguage->languageText("word_save"));
	$button->setToSubmit();
	$form->addToForm($button->show());
	echo $form->show();
?>