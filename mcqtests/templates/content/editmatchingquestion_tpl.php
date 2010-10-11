<?php
/**
 * Template for editing matching question types.
 * @package mcqtests
 */

// set up layout template
$this->setLayoutTemplate('mcqtests_layout_tpl.php');
$matchingformmanager = $this->getObject('question_calculated_formmanager');

$testid = $test['id'];
$edit = true;
// display the form for editing this question
$questionContentStr.='<div id="matchingquestions">'.$matchingformmanager->matchingQForm($testid, $data, $edit).'</div>';

echo $questionContentStr;
?>