<?php
$formNumber = $this->getParam('formNumber',NULL);

if (isset($formNumber))
{
$objDBFormSubmitResults = $this->getObject('dbformbuilder_submit_results','formbuilder');
echo $objDBFormSubmitResults->deleteAllSubmissions($formNumber);

}
?>
