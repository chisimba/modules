<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$optionValue = $_REQUEST['optionValue'];
$optionLabel = $_REQUEST['optionLabel'];
$formElementName= $_REQUEST['formElementName'];

$layoutOption= $_REQUEST['layoutOption'];
$defaultSelected= $_REQUEST['defaultSelected'];

if ($defaultSelected == "on")
{
    $defaultSelected =true;
}
else
{
    $defaultSelected =false;
}
$objRadioEntity = $this->getObject('form_entity_radio','formbuilder');
$objRadioEntity->createFormElement($formElementName);

if ($objRadioEntity->insertOptionandValue($formElementName,$optionLabel,$optionValue,$defaultSelected,$layoutOption) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else {
    $postSuccessBoolean = 0;
}


?>

<div id="WYSIWYGRadio">
    <?php
    if ($postSuccessBoolean == 1)
    {
  echo $objRadioEntity->showWYSIWYGRadioEntity();
    }
 else {
        echo $postSuccessBoolean;
    }
    ?>
</div>
