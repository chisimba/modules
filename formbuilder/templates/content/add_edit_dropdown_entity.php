<?php

//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$optionValue = $this->getParam('optionValue');
$optionLabel = $this->getParam('optionLabel');
$formElementName= $this->getParam('formElementName');

$defaultSelected= $this->getParam('defaultSelected');

$formElementLabel= $this->getParam('formElementLabel');
$formElementLabelLayout= $this->getParam('formElementLabelLayout');

if ($defaultSelected == "on")
{
    $defaultSelected =true;
}
else
{
    $defaultSelected =false;
}
$objDDEntity = $this->getObject('form_entity_dropdown','formbuilder');
$objDDEntity->createFormElement($formElementName);

if ($objDDEntity->insertOptionandValue($formElementName,$optionLabel,$optionValue,$defaultSelected,$formElementLabel,$formElementLabelLayout) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else {
    $postSuccessBoolean = 0;
}


?>

<div id="WYSIWYGDropdown">
    <?php
    if ($postSuccessBoolean == 1)
    {
  echo $objDDEntity->showWYSIWYGDropdownEntity();
    }
 else {
        echo $postSuccessBoolean;
    }
    ?>
</div>
