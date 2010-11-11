<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$formNumber = $this->getParam('formNumber',NULL);

if (isset($formNumber))
{

$objFormEntityHandler = $this->getObject('form_entity_handler','formbuilder');
echo $objFormEntityHandler->deleteForm($formNumber);
$objDBFormSubmitResults = $this->getObject('dbformbuilder_submit_results','formbuilder');
$objDBFormSubmitResults->deleteAllSubmissions($formNumber);
}




?>
