<?php
//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));

$optionValue = $this->getParam('optionValue');
$optionLabel = $this->getParam('optionLabel');
$formElementName = $this->getParam('formElementName');
$layoutOption = $this->getParam('layoutOption');
$defaultSelected = $this->getParam('defaultSelected');
$formElementLabel = $this->getParam('formElementLabel');
$formElementLabelLayout = $this->getParam('formElementLabelLayout');

if ($defaultSelected == "on") {
    $defaultSelected = true;
} else {
    $defaultSelected = false;
}

$objCheckboxEntity = $this->getObject('form_entity_checkbox', 'formbuilder');


if ($objCheckboxEntity->createFormElement($formElementName, $optionValue, $optionLabel, $defaultSelected, $layoutOption,$formElementLabel,$formElementLabelLayout) == TRUE) {
    $postSuccessBoolean = 1;
} else {
    $postSuccessBoolean = 0;
}
?>

<div id="WYSIWYGCheckbox">
<?php
if ($postSuccessBoolean == 1) {
    echo $objCheckboxEntity->showWYSIWYGCheckboxEntity();
} else {
    echo $postSuccessBoolean;
}
?>
</div>
