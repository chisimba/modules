<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$optionValue = $_REQUEST['optionValue'];
$optionLabel = $_REQUEST['optionLabel'];
$formElementName= $_REQUEST['formElementName'];

$defaultSelected= $_REQUEST['defaultSelected'];

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

if ($objDDEntity->insertOptionandValue($formElementName,$optionLabel,$optionValue,$defaultSelected) == TRUE)
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
