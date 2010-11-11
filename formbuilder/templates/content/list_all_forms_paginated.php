<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$paginationRequestNumber = $this->getParam('paginationRequestNumber',NULL);
$paginationbatchSize = $this->getParam("paginationbatchSize",NULL);
$searchValue = $this->getParam("searchValue",NULL);
$objFormList=$this->getObject('view_form_list','formbuilder');
$objFormList->setNumberOfEntriesInPaginationBatch($paginationbatchSize);
if ($searchValue != NULL)
{
  echo $objFormList->showPaginationIndicator($paginationRequestNumber,$searchValue);
echo $objFormList->show($paginationRequestNumber,$searchValue);
}
 else {
  echo $objFormList->showPaginationIndicator($paginationRequestNumber,NULL);
echo $objFormList->show($paginationRequestNumber,NULL);
}


?>
