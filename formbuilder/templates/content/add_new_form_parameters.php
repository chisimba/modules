<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));



echo $formTitle = $_REQUEST['formTitle'];
echo $formLabel= $_REQUEST['formLabel'];
echo $formDescription= $_REQUEST['formDescription'];

 $postSuccessBoolean = 0;

$objDBNewFormParameters = $this->getObject('dbformbuilder_form_list','formbuilder');

if ($objDBNewFormParameters->checkDuplicateFormEntry(NULL,$formTitle) == TRUE)
{
    $formNumber =$objDBNewFormParameters->insertSingle($formTitle,$formLabel,$formDescription);
    $postSuccessBoolean = 1;
}
else
{
    $postSuccessBoolean = 0;
}
////$objLabelEntity->createFormElement($formElementName,$labelValue,$layoutOption);
//
//if ($objHTMLHeadingEntity->createFormElement($formElementName,$HTMLHeadingValue,$fontSize,$textAlignment) == TRUE)
//{
//    $postSuccessBoolean = 1;
//}
// else {
//    $postSuccessBoolean = 0;
//}


?>

<div id="insertFormDetailsSuccessParameter">
    <?php
    echo $postSuccessBoolean;
//    if ($postSuccessBoolean == 1)
//    {
//  echo $objHTMLHeadingEntity->showWYSIWYGHTMLHeadingEntity();
//    }
// else {
//        echo $postSuccessBoolean;
//    }
    ?>
</div>
<div id="insertFormNumber">
    <?php

    if ($postSuccessBoolean == 1)
    {
  echo $formNumber;
    }
 else {
        echo "Post Failure";
    }
    ?>
</div>