<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$HTMLHeadingValue = $_REQUEST['HTMLHeadingValue'];
$formElementName= $_REQUEST['formElementName'];
$fontSize= $_REQUEST['fontSize'];
$textAlignment = $_REQUEST['textAlignment'];


$objHTMLHeadingEntity = $this->getObject('form_entity_htmlheading','formbuilder');
//$objLabelEntity->createFormElement($formElementName,$labelValue,$layoutOption);

if ($objHTMLHeadingEntity->createFormElement($formElementName,$HTMLHeadingValue,$fontSize,$textAlignment) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else {
    $postSuccessBoolean = 0;
}


?>

<div id="WYSIWYGHTMLHeading">
    <?php
    if ($postSuccessBoolean == 1)
    {
  echo $objHTMLHeadingEntity->showWYSIWYGHTMLHeadingEntity();
    }
 else {
        echo $postSuccessBoolean;
    }
    ?>
</div>
