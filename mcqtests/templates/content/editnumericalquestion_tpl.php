<?php
/**
 * Template for editing matching question types.
 * @package mcqtests
 */

// set up layout template
$this->setLayoutTemplate('mcqtests_layout_tpl.php');

$numericalformmanager = $this->getObject('numerical_question');

$testid = $test['id'];
$edit = true;
// display the form for editing this question
$questionContentStr.='<div id="numericalquestions">'.$numericalformmanager->numericalQForm($testid, $data, $edit).'</div>';

echo $questionContentStr;
?>