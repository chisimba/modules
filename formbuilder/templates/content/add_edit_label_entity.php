<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$labelValue = $_REQUEST['labelValue'];

$formElementName= $_REQUEST['formElementName'];

$layoutOption= $_REQUEST['layoutOption'];

$layoutOption = trim("$layoutOption");

$objLabelEntity = $this->getObject('form_entity_label','formbuilder');
//$objLabelEntity->createFormElement($formElementName,$labelValue,$layoutOption);

if ($objLabelEntity->createFormElement($formElementName,$labelValue,$layoutOption) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else {
    $postSuccessBoolean = 0;
}


?>

<div id="WYSIWYGLabel">
    <?php
    if ($postSuccessBoolean == 1)
    {
  echo $objLabelEntity->showWYSIWYGLabelEntity();
    }
 else {
        echo $postSuccessBoolean;
    }
    ?>
</div>
