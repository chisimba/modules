<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));

//{"formElementName": formElementLabel, "textInputName": textInputName, "textInputValue" : defaultText, "textInputType": textInputType, "textInputSize": textInputSize, "maskedInputChoice" : maskedInputChoice};
//             var buttonDataToPost = {"formElementName": formElementLabel,
//                  "buttonName": buttonName, "buttonLabel" : buttonLabel,
//                  "submitOrResetButtonChoice": submitOrResetButtonChoice};

echo $formElementName = $_REQUEST['formElementName'];
echo $buttonName = $_REQUEST['buttonName'];

echo $buttonLabel = $_REQUEST['buttonLabel'];
echo $submitOrResetButtonChoice = $_REQUEST['submitOrResetButtonChoice'];



$objButtonEntity = $this->getObject('form_entity_button','formbuilder');
//$objButtonEntity->createFormElement($buttonFormName,$buttonName,$buttonLabel,$isSetToResetOrSubmit)

if ($objButtonEntity->createFormElement($formElementName,$buttonName,$buttonLabel,$submitOrResetButtonChoice) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else
     {
    $postSuccessBoolean = 0;
}


?>
<div id="WYSIWYGButton">
    <?php
    if ($postSuccessBoolean == 1)
    {
  echo $objButtonEntity->showWYSIWYGButtonEntity();
    }
 else
     {
        echo $postSuccessBoolean;
    }
    ?>
</div>