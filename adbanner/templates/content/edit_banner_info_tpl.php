<?php
/*impoting classes*/
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');

$id = $this->getParam('id');

$info=$this->objAddAd->getBanner($id);
$ti = null;
$de = null;
$si = null;
$act = null;

foreach ($info as $banner) {
	$ti = $banner['banner_title'];
	$de = $banner['comment_desc'];
	$wi = $banner['banner_width'];
	$he = $banner['banner_height'];
	$si = $wi."x".$he;
	$ac = $banner['is_active']; 
}

/*declare objects*/
//--banner id will be kept in a hidden input box to be sent along with the form
$bannerID = new textinput('id','','hidden',25);
$bannerID->setValue($id);

$pageHeading = new htmlheading('Edit Banner Information');
$bannerTitle = new textinput('title','','',25);
$bannerTitle->setValue($ti);

$bannerDesc  = new textarea('desc','',15,50);
$bannerDesc->setValue($de);

$isActiv = new radio('active');
$isActiv->addOption('0','NO');
$isActiv->addOption('1','YES');

if ($ac==0) {
	$isActiv->setSelected(0);
}
else {
	$isActiv->setSelected(1);
}



$bannerSize = new dropdown('size');
$bannerSize->addOption(''.$wi.' '.$he.'',''.$si.'');
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


$btnSubmit = new button('submitbutton', 'Update');
$btnCancel = new button('cancel','Cancel');
$btnSubmit->setToSubmit();
$btnCancel->setOnClick("window.location='".$this->uri(NULL)."';");

/* Adding to the form */
$form = new form('editinfoform',$this->uri(array('action'=>'submiteditinfoform')));
$form->setEncType("multipart/form-data");
$form->addRule("title","Banner Title required","required");
$form->addRule("desc","Banner Description required","required");
$form->addRule("size","Banner Size required","required");
$form->addRule("active","Banner 'Is Active' required","required");

$form->addToForm($bannerID->show());

$form->addToForm("<table><th>".$pageHeading->show()."</th></table>");
$form->addToForm("<table><tr><td>Banner Title:&nbsp;".$bannerTitle->show()."</td></tr>");
$form->addToForm("<tr><td colspan=\"2\">Banner Description:<br>".$bannerDesc->show()."</td></tr>");
$form->addToForm("<tr><td colspan=\"2\">Is Active:<br>".$isActiv->show()."</td></tr>");
$form->addToForm("<tr><td colspan=\"2\">Banner Size:<br>".$bannerSize->show()."</td></tr>");
$form->addToForm("<tr><td>".$btnSubmit->show()."</td><td>".$btnCancel->show()."</td></tr></table>");

echo $form->show();
?>
