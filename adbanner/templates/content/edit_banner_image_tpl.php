<?php
/*impoting classes*/
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$bannerid = $this->getParam('id');

$info=$this->objAddAd->getBanner($bannerid);
$imageId = null;
$imagePath = null;
$imageURL = null;


$pageHeading = new htmlheading('Edit Banner Image');

$form = new form('editimageform','');
$form->setEncType("multipart/form-data");

$form->addToForm("<table><th colSpan='2'>".$pageHeading->show()."</th></table>");

foreach ($info as $image) {
	$imageId = $image['id'];
	$imagePath = $image['image_path'];
	$imageURL = $image['image_url'];
	
	$form->addToForm("<table><tr ><td colSpan='2'><img src='".$imagePath."'></td></tr>");
	$form->addToForm("<tr colSpan='2'><td>Thkis is image links to: ".$imageURL."</td></tr>");

	$changeImageLink=$this->uri(array('action'=>'changeimage','id'=>''.$imageId.''),"adbanner");
	$objChangeImgIcon = $this->newObject('geticon', 'htmlelements');
	$objChangeImgIcon->title="Change Image";
	$changeImage = $objChangeImgIcon->getLinkedIcon($changeImageLink, 'edit');

	$objEditInfoIcon = $this->newObject('geticon', 'htmlelements');
	$objEditInfoIcon->title="Edit Image info";
	$editInfoLink=$this->uri(array('action'=>'editimageinfo','id'=>''.$imageId.''));
	$editInfo = $objEditInfoIcon->getLinkedIcon($editInfoLink, 'editmetadata');
	
	$form->addToForm("<tr><td>".$changeImage."&nbsp;&nbsp;&nbsp;".$editInfo."</td></tr>");	}
$form->addToForm("</table>");
echo $form->show();
?>
