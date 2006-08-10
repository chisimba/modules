<?php 
/* A PHP template for the helloworld module output. */
?>
	<div style="background-color: #008080; padding:5px;">
	<div style="background-color: #000080; padding:5px;">
	<div style="background-color: #FFFFFF; padding:5px;">
<?php
	$this->loadClass("form","htmlelements");
	$form = new form("mainform",
		$this->uri(array(
	    	'module'=>'instantmessaging',
			'action'=>'ICQConfirm'
		))
	);
	$form->setDisplayType(3);
	$this->loadClass("textinput","htmlelements");
	$form->addToForm("<b>ICQ #</b>");
	$form->addToForm(new textinput("icq",""));
	$this->loadClass("textarea","htmlelements");
	$form->addToForm(new textarea("text", "", 5, 35));
	$this->loadClass("button","htmlelements");
	$button = new button("send", $objLanguage->languageText("word_send"));
	$button->setToSubmit();
	$form->addToForm($button);
	echo $form->show();
	echo "<a href=\"" . 
		$this->uri(array(
	    	'module'=>'instantmessaging',
			'action'=>'showusers',
		))	
	. "\">" . "Return to IM" . "</a>";
	$icon=&$this->newObject('geticon','htmlelements');
	$icon->setIcon('close');
	$icon->alt=$this->objLanguage->languageText("im_closewindow");
    $icon->align = "absmiddle";
	$icon->extra=' onclick="window.close()" ';
	echo "<p>" . $icon->show() . "</p>";
?>
	</div>
	</div>
	</div>
