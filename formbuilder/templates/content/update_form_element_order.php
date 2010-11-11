<?php

$formElementOrderString = $this->getParam('formElementOrderString',NULL);
$formNumber = $this->getParam('formNumber',NULL);

if (isset($formElementOrderString))
{
$objFormEntityHandler = $this->getObject('form_entity_handler','formbuilder');
    //$objDBFormElements = $this->getObject('dbformbuilder_form_elements','formbuilder');
$formElementOrderArray = explode(",", $formElementOrderString);
$objFormEntityHandler->updateExistingFormElementOrder($formElementOrderArray,$formNumber);
//print_r($formElementOrderArray);
//$formOrder=1;
//foreach ($formElementOrderArray as $formElementName)
//{
//
//    echo $objDBFormElements->updatFormElementOrder($formNumber,$formElementName,$formOrder);
//    $formOrder++;
//}
}
?>
