<?php

$formName = $this->getParam('formName');
$formNumber = $this->getParam('formNumber');
$formElementType = $this->getParam('formElementType');
$objInsertElementFormEntity = $this->getObject('form_element_editor','formbuilder');
if (isset($formElementType)) {
    switch ($formElementType) {

        case 'radio':
            echo $objInsertElementFormEntity->showInsertRadioForm($formName);
            break;

        case 'checkbox':
            echo $objInsertElementFormEntity->showInsertCheckboxForm($formName);
            break;

        case 'dropdown':
             echo $objInsertElementFormEntity->showInsertDropDownForm($formName);
            break;
        case 'label':
            echo $objInsertElementFormEntity->showInsertLabelForm($formName);
            break;
        case 'HTML_heading':
            echo $objInsertElementFormEntity->showInsertHTMLHeadingForm($formName);
            break;
        case 'datepicker':
            echo $objInsertElementFormEntity->showInsertDatePickerForm($formName);
            break;
        case 'text_input':
            echo $objInsertElementFormEntity->showInsertTextInputForm($formName);
            break;
        case 'text_area':
            echo $objInsertElementFormEntity->showInsertTextAreaForm($formName);
            break;
        case 'button':
            echo $objInsertElementFormEntity->showInsertButtonForm($formName);
            break;
        case 'multiselectable_dropdown':
            echo $objInsertElementFormEntity->showInsertMSDropDownForm($formName);
            break;
        default:
           echo $postSuccess = 0;
            break;
    }
}
?>
