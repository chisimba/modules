<?php
// load form
$form = $this->objFormManager->createSCQList($testId, Null, $deletemsg, $addmsg);
echo $form;
// load form
$form = $this->objFormManager->createDescriptionList(Null, $testId);
echo $form;
?>