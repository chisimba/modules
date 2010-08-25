<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');
//{"formElementName": formElementLabel, "textInputName": textInputName, "textInputValue" : defaultText, "textInputType": textInputType, "textInputSize": textInputSize, "maskedInputChoice" : maskedInputChoice};



echo $formElementName = $_REQUEST['formElementName'];
echo $textAreaName = $_REQUEST['textAreaName'];

echo $textAreaValue = $_REQUEST['textAreaValue'];
echo $ColumnSize = $_REQUEST['ColumnSize'];

echo $RowSize = $_REQUEST['RowSize'];

echo $simpleOrAdvancedHAChoice= $_REQUEST['simpleOrAdvancedHAChoice'];
echo $toolbarChoice= $_REQUEST['toolbarChoice'];


//
//
$objTextAreaEntity = $this->getObject('form_entity_textarea','formbuilder');
$objTextAreaEntity->createFormElement($textAreaFormName,$textAreaName);

if ($objTextAreaEntity->insertTextAreaParameters($formElementName,$textAreaName,$textAreaValue,$ColumnSize,$RowSize,$simpleOrAdvancedHAChoice,$toolbarChoice) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else
     {
    $postSuccessBoolean = 0;
}


?>
<div id="WYSIWYGTextArea">
    <?php
    if ($postSuccessBoolean == 1)
    {
  echo $objTextAreaEntity->showWYSIWYGTextAreaEntity();
    }
 else
     {
        echo $postSuccessBoolean;
    }
    ?>
</div>