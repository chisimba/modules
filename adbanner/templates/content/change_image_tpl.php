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
$pageHeading = new htmlheading('Change Image');
$bannerImage = new textinput('image_file','','file','');
$imageURL = new textinput('url','','',50);
$imageURL->setvalue($imageUrl);
$btnSubmit = new button('submitbutton', 'Submit and Upload');
$btnCancel = new button('cancel','Cancel');
$btnSubmit->setToSubmit();
$btnCancel->setOnClick("window.location='".$this->uri(NULL)."';");

/* Adding to the form */
$form = new form('form',$this->uri(array('action'=>'changeimageform', 'id'=>$id)));
$form->setEncType("multipart/form-data");
$form->addRule("image_file","Image to Upload required","required");
$form->addRule("url","URL to which image links is required","required");

$form->addToForm("<table><th>".$pageHeading->show()."</th></table>");

$form->addToForm("<tr><td>URL to which Image links:<br>".$imageURL->show()."</td></tr>");
$form->addToForm("</br>Upload this image:<br/>");
$form->addToForm("".$bannerImage->show()."</td></tr><br/>");

$form->addToForm("  ".$btnSubmit->show()."  ".$btnCancel->show());
echo $form->show();
?>
