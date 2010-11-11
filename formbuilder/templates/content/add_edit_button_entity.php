<?php
//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));

$formElementName = $_REQUEST['formElementName'];
$buttonName = $_REQUEST['buttonName'];
$buttonLabel = $_REQUEST['buttonLabel'];
$submitOrResetButtonChoice = $_REQUEST['submitOrResetButtonChoice'];

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