<?php

//$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$this->loadClass('form', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
//echo $formTitle = $this->getParam('formTitle',NULL);
echo $formLabel = $this->getParam('formLabel',NULL);
echo $formEmail = $this->getParam('formEmail',NULL);
echo $submissionOption = $this->getParam('submissionOption',NULL);
echo $formDescription = $this->getParam('formDescription',NULL);

$stripedFormLabel = preg_replace("/[^a-zA-Z0-9s]/", "", $formLabel);
$formTitle = $stripedFormLabel.rand(1000,99999) .time();
$postSuccessBoolean = 0;

$objDBNewFormParameters = $this->getObject('dbformbuilder_form_list', 'formbuilder');

if ($objDBNewFormParameters->checkDuplicateFormEntry(NULL, $formTitle) == TRUE) {
    $formNumber = $objDBNewFormParameters->insertSingle($formTitle, $formLabel, $formDescription,$formEmail,$submissionOption);
    $postSuccessBoolean = 1;
} else {
    $postSuccessBoolean = 0;
}
?>

<div id="insertFormDetailsSuccessParameter">
<?php
echo $postSuccessBoolean;
?>
</div>
<div id="insertFormNumber">
<?php
if ($postSuccessBoolean == 1) {
//echo $formNumber;
    $objForm = new form('formDetails', $this->uri(array("action" => "designWYSIWYGForm"), "formbuilder"));
    $formNumber = new hiddeninput('formNumber', $formNumber);
    $objForm->addToForm($formNumber->show());

    $formTitle = new hiddeninput('formTitle', $formTitle);
    $objForm->addToForm($formTitle->show());

    $formLabel = new hiddeninput('formLabel', $formLabel);
    $objForm->addToForm($formLabel->show());

    $formEmail = new hiddeninput('formEmail', $formEmail);
    $objForm->addToForm($formEmail->show());

    $submissionOption = new hiddeninput('submissionOption',$submissionOption);
    $objForm->addToForm($submissionOption->show());

    $formDescription = new hiddeninput('formDescription', $formDescription);
    $objForm->addToForm($formDescription->show());
    echo $objForm->show();
} else {
    echo "Post Failure";
}
?>
</div>