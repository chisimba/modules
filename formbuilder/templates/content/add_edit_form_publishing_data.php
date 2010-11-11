<?php

echo $formNumber = $this->getParam('formNumber',NULL);
echo $publishingOption = $this->getParam('publishingOption',NULL);
echo $urlChoice = $this->getParam('urlChoice',NULL);
echo $chisimbaAction = $this->getParam('chisimbaAction',NULL);
echo $chisimbaModule = $this->getParam('chisimbaModule',NULL);
echo $formParameters = $this->getParam('formParameters',NULL);
echo $divertDelay = $this->getParam('divertDelay',NULL);


if (isset($formNumber))
{
$objDBFormMetadata= $this->getObject('dbformbuilder_form_list','formbuilder');

    $objDBFormPublishingOptions = $this->getObject('dbformbuilder_publish_options','formbuilder');
if ($objDBFormPublishingOptions->checkIfPushlishingDataExists($formNumber) ==FALSE)
{
  $formName = $objDBFormMetadata->getFormName($formNumber);
    $objDBFormPublishingOptions->insertSingle($formNumber,$formName,$publishingOption, $urlChoice,$chisimbaModule,$chisimbaAction,$formParameters,$divertDelay);
}
else
{
         switch ($publishingOption)
        {

            case NULL:
                  $objDBFormPublishingOptions->unpublishForm($formNumber,$publishingOption);
                echo "right";
            break;

case 'simple':
     $objDBFormPublishingOptions->updateSimplePublishingParameters($formNumber,$publishingOption, $urlChoice,$divertDelay);
    break;

case 'advanced':
    $objDBFormPublishingOptions->updateAdvancedPublishingParameters($formNumber,$publishingOption,$chisimbaModule,$chisimbaAction,$formParameters,$divertDelay);
    break;

    default:
                $postSuccess = 0;
            break;
               // $this->clickedAdd=$this->getParam('clickedadd');

                //return "home_tpl.php";
        }
}
}

?>
