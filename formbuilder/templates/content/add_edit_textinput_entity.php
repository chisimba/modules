<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));

//{"formElementName": formElementLabel, "textInputName": textInputName, "textInputValue" : defaultText, "textInputType": textInputType, "textInputSize": textInputSize, "maskedInputChoice" : maskedInputChoice};
echo $formElementName = $_REQUEST['formElementName'];
echo $textInputName = $_REQUEST['textInputName'];

echo $textInputValue = $_REQUEST['textInputValue'];
echo $textInputType = $_REQUEST['textInputType'];

echo $textInputSize = $_REQUEST['textInputSize'];

echo $maskedInputChoice= $_REQUEST['maskedInputChoice'];

$objTextInputEntity = $this->getObject('form_entity_textinput','formbuilder');
$objTextInputEntity->createFormElement($formElementName,$textInputName);

if ($objTextInputEntity->insertTextInputParameters($formElementName,$textInputName,$textInputValue,$textInputType,$textInputSize,$maskedInputChoice) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else
     {
    $postSuccessBoolean = 0;
}


?>
<div id="WYSIWYGTextInput">
    <?php
    if ($postSuccessBoolean == 1)
    {
  echo $objTextInputEntity->showWYSIWYGTextInputEntity();
    }
 else
     {
        echo $postSuccessBoolean;
    }
    ?>
</div>