<?php

$formElementName = $this->getParam('formElementName',NULL);
$formNumber = $this->getParam('formNumber',NULL);

if (isset($formElementName))
{

$objDBFormElements = $this->getObject('dbformbuilder_form_elements','formbuilder');
$objDBRadioElements = $this->getObject('dbformbuilder_radio_entity','formbuilder');
$objFormEntityHandler = $this->getObject('form_entity_handler','formbuilder');
echo $objFormEntityHandler->deleteExisitngFormElement($formElementName,$formNumber);
//  $formElementType = $objDBFormElements->deleteFormElement($formElementName,$formNumber);
//
//  if ($formElementType == false)
//  {
//   echo "NOT OK";
//  }
//  else
//  {
//   switch ($formElementType)
//        {
//
//            case 'radio':
//                $postSuccess = $objDBRadioElements->deleteFormElement($formElementName);
//            break;
//
//case 'checkbox':
//     $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//    break;
//
//case 'dropdown':
//     $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//    break;
//case 'label':
//  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//    break;
//case 'HTML_heading':
//  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//    break;
//case 'datepicker':
//  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//    break;
//case 'text_input':
//  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//    break;
//case 'text_area':
//  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//    break;
//case 'button':
//  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//    break;
//case 'multiselectable_dropdown':
// $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
//        break;
//    default:
//                $postSuccess = 2;
//            break;
//               // $this->clickedAdd=$this->getParam('clickedadd');
//
//                //return "home_tpl.php";
//        }
 // }
//$formElementOrderArray = explode(",", $formElementOrderString);
//
////print_r($formElementOrderArray);
//$formOrder=1;
//foreach ($formElementOrderArray as $formElementName)
//{
//
//  
//    $formOrder++;
//}
}
?>

