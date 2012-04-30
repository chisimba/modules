<?php



$formNumber = $this->getParam('formNumber');
$formElementName = $this->getParam('formElementName');
$formElementType = $this->getParam('formElementType');
$objInsertElementFormEntity = $this->getObject('form_element_editor', 'formbuilder');
if (isset($formElementType)) {
    switch ($formElementType) {

        case 'witsCCMSFormElementRadio':
            echo $objInsertElementFormEntity->showEditRadioForm($formNumber,$formElementName);
            break;
        case 'checkbox':
         
            break;
        case 'dropdown':
      
            break;
        case 'witsCCMSFormElementLabel':
            echo $objInsertElementFormEntity->showEditLabelForm($formNumber,$formElementName);
            break;
        case 'witsCCMSFormElementHTMLHeading':
           echo $objInsertElementFormEntity->showEditHTMLHeadingForm($formNumber,$formElementName);
            break;
        case 'witsCCMSFormElementDatePicker':
          echo $objInsertElementFormEntity->showEditDatePickerForm($formNumber,$formElementName);
            break;
        case 'witsCCMSFormElementTextInput':
            echo $objInsertElementFormEntity->showEditTextInputForm($formNumber,$formElementName);
            break;
        case 'witsCCMSFormElementTextArea':
            echo $objInsertElementFormEntity->showEditTextAreaForm($formNumber,$formElementName);
            break;
        case 'button':
     
            break;
        case 'multiselectable_dropdown':
     
            break;
        default:
            echo $postSuccess = 0;
            break;
    }
}

?>
