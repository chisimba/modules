<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$formNumber=$this->getParam("formNumber",NULL);
if (isset($formNumber))
{
$objFormConstructor = $this->getObject('form_entity_handler','formbuilder');


echo ($objFormConstructor->buildWYSIWYGForm($formNumber));
}
?>
