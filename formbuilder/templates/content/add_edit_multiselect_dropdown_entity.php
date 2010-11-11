<?php

echo $optionValue = $this->getParam('optionValue');
echo $optionLabel = $this->getParam('optionLabel');
echo $formElementName = $this->getParam('formElementName');

echo $defaultSelected = $this->getParam('defaultSelected');
echo $menuSize = $this->getParam('menuSize');
echo $formElementLabel = $this->getParam('formElementLabel');
echo $formElementLabelLayout = $this->getParam('formElementLabelLayout');
if ($defaultSelected == "on") {
    $defaultSelected = true;
} else {
    $defaultSelected = false;
}

$objMSDDEntity = $this->getObject('form_entity_multiselect_dropdown', 'formbuilder');
$objMSDDEntity->createFormElement($formElementName);

if ($objMSDDEntity->insertOptionandValue($formElementName, $optionLabel, $optionValue, $defaultSelected, $menuSize,$formElementLabel,$formElementLabelLayout) == TRUE) {
    $postSuccessBoolean = 1;
} else {
    $postSuccessBoolean = 0;
}
?>

<div id="WYSIWYGMSDropdown">
<?php
if ($postSuccessBoolean == 1) {
    echo $objMSDDEntity->showWYSIWYGMultiSelectDropdownEntity();
} else {
    echo $postSuccessBoolean;
}
?>
</div>
