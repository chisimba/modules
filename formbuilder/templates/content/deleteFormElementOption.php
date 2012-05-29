<?php
$formElementType =  $this->getParam("formElementType");
$optionID = $this->getParam("optionID");


switch ($formElementType) {

    case 'radio':
        $objRadioEntity = $this->getObject('dbformbuilder_radio_entity', 'formbuilder');
       $objRadioEntity->deleteSingle($optionID);
        break;

    case 'checkbox':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;

    case 'dropdown':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;
    case 'label':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;
    case 'HTML_heading':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;
    case 'datepicker':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;
    case 'text_input':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;
    case 'text_area':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;
    case 'button':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;
    case 'multiselectable_dropdown':
        $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber, $formName, $formElementType, $formElementName);
        break;
    default:
        $postSuccess = 2;
        break;
}
?>
