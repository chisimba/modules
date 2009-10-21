<?php
/*impoting classes*/
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
/*declare objects*/
$pageHeading = new htmlheading('Add Banner');
$bannerTitle = new textinput('title','','',25);
$bannerDesc  = new textarea('desc','',15,50);
$bannerImage = new textinput('image_file','','file','');
$bannerSize = new dropdown('size');
$bannerSize->addOption('',' ');
$bannerSize->addOption('728 90','Leaderboard 728x90');
$bannerSize->addOption('468 60','Full banner / Impact banner 468x60');
$bannerSize->addOption('234 60','Half banner 234x60');
$bannerSize->addOption('120 90','Button 1 120x90');
$bannerSize->addOption('120 60','Button 2 120x60');
$bannerSize->addOption('88 31','Micro bar 88x31');
$bannerSize->addOption('80 15','Micro button 80x15');
$bannerSize->addOption('120 240','Vertical banner 120x240');
$bannerSize->addOption('125 125','Square button 125x125');
$bannerSize->addOption('120 600','Thin Skyscraper 120x600');
$bannerSize->addOption('160 600','Standard skyscraper 160x600');
$bannerSize->addOption('300 600','Half-page 300x600');

$imageURL = new textinput('url','','',50);
$imageURL->setvalue("http://");

$btnSubmit = new button('submitbutton', 'Submit and Upload');
$btnCancel = new button('cancel','Cancel');
$btnSubmit->setToSubmit();
$btnCancel->setOnClick("window.location='".$this->uri(NULL)."';");

/* Adding to the form */
$form = new form('addbannerform',$this->uri(array('action'=>'submitaddbannerform')));
$form->setEncType("multipart/form-data");
$form->addRule("title","Banner Title required","required");
$form->addRule("desc","Banner Description required","required");
$form->addRule("image_file","Image to Upload required","required");
$form->addRule("url","URL to which image links is required","required");
$form->addRule("size","Banner Size required","required");

$form->addToForm("<table><th>".$pageHeading->show()."</th></table>");
$form->addToForm("<table><tr><td>Banner Title:<br>".$bannerTitle->show()."</td></tr>");

$form->addToForm("<tr><td colspan=\"2\">Banner Description:<br>".$bannerDesc->show()."</td></tr>");
$form->addToForm("<tr><td colspan=\"2\">Banner Size:<br>".$bannerSize->show()."</td></tr>");

$form->addToForm("<tr><td>URL to which Image links:<br>".$imageURL->show()."</td></tr>");
$form->addToForm("<tr><td>Upload this image:<br/>");
$form->addToForm("".$bannerImage->show()."</td></tr><br/>");

$form->addToForm("<tr><td>".$btnSubmit->show()."</td><td>".$btnCancel->show()."</td></tr></table>");

echo $form->show();
?>
