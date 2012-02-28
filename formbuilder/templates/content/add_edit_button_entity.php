<?php
///Request all the parameters from the post from the
///Ajx function and store them into temporary variables.
$formElementName = $this->getParam('formElementName');
$buttonName = $this->getParam('buttonName');
$buttonLabel = $this->getParam('buttonLabel');
$submitOrResetButtonChoice = $this->getParam('submitOrResetButtonChoice');

$objButtonEntity = $this->getObject('form_entity_button', 'formbuilder');

if ($objButtonEntity->createFormElement($formElementName, $buttonName, $buttonLabel, $submitOrResetButtonChoice) == TRUE) {
    $postSuccessBoolean = 1;
} else {
    $postSuccessBoolean = 0;
}
?>
<div id="WYSIWYGButton">
<?php
if ($postSuccessBoolean == 1) {
    echo $objButtonEntity->showWYSIWYGButtonEntity();
} else {
    echo $postSuccessBoolean;
}
?>
</div>