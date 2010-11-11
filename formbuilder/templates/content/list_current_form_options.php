<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$formNumber=$this->getParam("formNumber",NULL);
if (isset($formNumber))
{
$objFormMenuConstructor = $this->getObject('view_form_list','formbuilder');


echo ($objFormMenuConstructor->showFormOptionsMenu($formNumber));
}
?>
