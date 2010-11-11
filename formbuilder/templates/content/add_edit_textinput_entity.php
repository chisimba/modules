<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));

//{"formElementName": formElementLabel, "textInputName": textInputName, "textInputValue" : defaultText, "textInputType": textInputType, "textInputSize": textInputSize, "maskedInputChoice" : maskedInputChoice};
echo $formNumber = $this->getParam('formNumber');
echo $formElementName = $this->getParam('formElementName');
echo $textInputName = $this->getParam('textInputName');

echo $textInputValue = $this->getParam('textInputValue');
echo $textInputType = $this->getParam('textInputType');

echo $textInputSize = $this->getParam('textInputSize');

echo $maskedInputChoice= $this->getParam('maskedInputChoice');
echo $formElementLabel= $this->getParam('formElementLabel');
echo $formElementLabelLayout= $this->getParam('formElementLabelLayout');

$objTextInputEntity = $this->getObject('form_entity_textinput','formbuilder');
$objTextInputEntity->createFormElement($formElementName,$textInputName);

if ($objTextInputEntity->insertTextInputParameters($formElementName,$textInputName,$textInputValue,$textInputType,$textInputSize,$maskedInputChoice,$formElementLabel,$formElementLabelLayout) == TRUE)
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