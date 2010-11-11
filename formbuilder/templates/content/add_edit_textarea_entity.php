<?php

//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
//$this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');

$formElementName =$this->getParam('formElementName');
$textAreaName = $this->getParam('textAreaName');
$textAreaValue = $this->getParam('textAreaValue');
$ColumnSize = $this->getParam('ColumnSize');
$RowSize = $this->getParam('RowSize');
$simpleOrAdvancedHAChoice= $this->getParam('simpleOrAdvancedHAChoice');
$toolbarChoice= $this->getParam('toolbarChoice');
$formElementLabel= $this->getParam('formElementLabel');
$labelLayout= $this->getParam('labelLayout');


$objTextAreaEntity = $this->getObject('form_entity_textarea','formbuilder');
$objTextAreaEntity->createFormElement($textAreaFormName,$textAreaName);

if ($objTextAreaEntity->insertTextAreaParameters($formElementName,$textAreaName,$textAreaValue,$ColumnSize,$RowSize,$simpleOrAdvancedHAChoice,$toolbarChoice,$formElementLabel,$labelLayout) == TRUE)
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