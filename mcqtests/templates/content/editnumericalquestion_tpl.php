<?php
/**
 * Template for editing matching question types.
 * @package mcqtests
 */

// set up layout template
$this->setLayoutTemplate('mcqtests_layout_tpl.php');

$numericalformmanager = $this->getObject('numerical_question');

$testid = $this->getParam('id');
if($mode == 'edit') {
    $edit = true;
    $questionId = $this->getParam('questionId');
} else {
    $edit = false;
}
// display the form for editing this question
$questionContentStr.='<div id="numericalquestions">'.$numericalformmanager->numericalQForm($testid, $data, $edit, $questionId).'</div>';

echo $questionContentStr;
?>