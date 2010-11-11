<?php
//$arraylist = array('title','test','commenttxt');
//$comma_separated = implode(",", $arraylist);
 //$hi = new hiddeninput("formNumber", $formNumber);
//$hi->show();
//echo $formNumber;

$objFormConstructor = $this->getObject('form_entity_handler','formbuilder');


echo ($objFormConstructor->buildForm($formNumber));
?>
