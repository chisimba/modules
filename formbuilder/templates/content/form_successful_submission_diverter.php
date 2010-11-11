<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
echo $formNumber= $this->getParam("formNumber",NULL);
echo $submitNumber= $this->getParam("submitNumber",NULL);
 $objDBFormPublishingOptions = $this->getObject('dbformbuilder_publish_options','formbuilder');
 $objDBFormSubmitResults = $this->getObject('dbformbuilder_submit_results','formbuilder');
   $formPublishingData =  $objDBFormPublishingOptions->getFormPublishingData($formNumber);
 $submitResultArray = $objDBFormSubmitResults->getParticularSubmitResults($submitNumber);

$formPublishingOption=  $formPublishingData["0"]['publishoption'];
$chisimbaParameters =  $formPublishingData["0"]['chisimbaparameters'];
$chisimbaModule= $formPublishingData["0"]['chisimbamodule'];
$chisimbaAction= $formPublishingData["0"]['chisimbaaction'];
if ($formPublishingOption != 'advanced')
{
 echo "Critical Internal Error. This form has been submitted illegally or publishing parameters are
     corrupted. Please contact your software administrator.";
}
 else {
    if ($chisimbaParameters == "yes")
    {
    $nextActionParameterArray=array();
        foreach ( $submitResultArray as $thisSubmitResult)
{
$formElementName= $thisSubmitResult['formelementname'];
  $formElementValue= $thisSubmitResult['formelementvalue'];
$nextActionParameterArray[$formElementName]=$formElementValue;
}
$this->nextAction($chisimbaAction,$nextActionParameterArray,$chisimbaModule);
    }
 else {
      $this->nextAction($chisimbaAction,'',$chisimbaModule);
    }

}

 









//$this->nextAction($chisimbaAction,$nextActionParameterArray,$chisimbaModule);

?>
