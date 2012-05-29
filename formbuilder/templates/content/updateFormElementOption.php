<?php
$formElementType =  $this->getParam("formElementType");
$optionID = $this->getParam("optionID");
$formNumber = $this->getParam("formNumber");
$optionValue = $this->getParam('optionValue');
$optionLabel = $this->getParam('optionLabel');
$formElementName = $this->getParam('formElementName');

$layoutOption = $this->getParam('layoutOption');
$defaultSelected = $this->getParam('defaultSelected');
$formElementLabelLayout = $this->getParam('formElementLabelLayout');
$formElementLabel = $this->getParam('formElementLabel');

if ($defaultSelected == "on") {
    $defaultSelected = true;
} else {
    $defaultSelected = false;
}

$postSuccess = "0";
switch ($formElementType) {

    case 'radio':
        $objRadioEntity = $this->getObject('form_entity_radio', 'formbuilder');

        $postSuccess = $objRadioEntity->updateOptionandValue($optionID,$formNumber,$formElementName, $optionLabel, $optionValue, $defaultSelected, $layoutOption,$formElementLabel,$formElementLabelLayout);
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

echo $postSuccess;

?>
