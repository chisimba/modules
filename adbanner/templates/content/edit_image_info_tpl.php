<?php
$id = $this->getParam('id');
$info=$this->objAddAd->getBanner($id);
$imagePath=null;
foreach ($info as $image) {
	$imageUrl = $image['image_url'];
}

/*impoting classes*/
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('button', 'htmlelements');


/*declare objects*/
$pageHeading = new htmlheading('Edit Image Info');
$imageURL = new textinput('url','','',50);
$imageURL->setvalue($imageUrl);
$btnSubmit = new button('submitbutton', 'Edit');
$btnCancel = new button('cancel','Cancel');
$btnSubmit->setToSubmit();
$btnCancel->setOnClick("window.location='".$this->uri(NULL)."';");

/* Adding to the form */
$form = new form('form',$this->uri(array('action'=>'editimageinfoform', 'id'=>$id)));
$form->addRule("url","URL to which image links is required","required");

$form->addToForm("<table><th>".$pageHeading->show()."</th></table>");

$form->addToForm("<tr><td>URL to which Image links:<br>".$imageURL->show()."</td></tr>");

$form->addToForm("  ".$btnSubmit->show()."  ".$btnCancel->show());
echo $form->show();
?>
