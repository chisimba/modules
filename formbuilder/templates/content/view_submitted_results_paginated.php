<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 $paginationRequestNumber = $this->getParam('paginationRequestNumber',NULL);
  $numberOfEntriesInPaginationBatch = $this->getParam('numberOfEntriesInPaginationBatch',NULL);
    $latestOrAllResults = $this->getParam('latestOrAllResults',NULL);
$formNumber = $this->getParam('formNumber',NULL);
if (isset( $paginationRequestNumber))
{
 $objSubmitResultsHandler = $this->getObject('form_submit_results_handler','formbuilder');
 $objSubmitResultsHandler->setFormNumber($formNumber);
 $objSubmitResultsHandler->setNumberOfEntriesInPaginationBatch($numberOfEntriesInPaginationBatch);
 echo $objSubmitResultsHandler->getMoreResultsPerPaginationRequest($latestOrAllResults, $paginationRequestNumber);
}

?>
