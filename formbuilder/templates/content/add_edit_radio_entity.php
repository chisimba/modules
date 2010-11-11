<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
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
$objRadioEntity = $this->getObject('form_entity_radio', 'formbuilder');
$objRadioEntity->createFormElement($formElementName);

if ($objRadioEntity->insertOptionandValue($formElementName, $optionLabel, $optionValue, $defaultSelected, $layoutOption,$formElementLabel,$formElementLabelLayout) == TRUE) {
    $postSuccessBoolean = 1;
} else {
    $postSuccessBoolean = 0;
}
?>

<div id="WYSIWYGRadio">
<?php
if ($postSuccessBoolean == 1) {
    echo $objRadioEntity->showWYSIWYGRadioEntity();
} else {
    echo $postSuccessBoolean;
}
?>
</div>
