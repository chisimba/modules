<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

if ($mode == 'edit') {
    $headStr = $this->objLanguage->languageText('mod_assignment_editassignment', 'assignment', 'Edit Assignment').': '.$assignment['name'];
    $action = 'updateassignment';
} else {
    $headStr = $this->objLanguage->languageText('mod_assignment_createassignment', 'assignment', 'Create a New Assignment');
    $action = 'saveassignment';
}

$header = new htmlHeading();
$header->type = 1;
$header->str = $headStr;

echo $header->show();

$table = $this->newObject('htmltable', 'htmlelements');

$table->startRow();
$label = new label ($this->objLanguage->languageText('mod_assignment_assignmentname', 'assignment', 'Assignment Name'), 'input_name');
$textinput = new textinput('name');
$textinput->size = 60;
if ($mode == 'edit') {
    $textinput->value = $assignment['name'];
}
$table->addCell($label->show(), 200);
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_assignmenttype', 'assignment', 'Assignment Type'));

if ($mode == 'edit') {
    $canChangeType = $this->objAssignmentSubmit->getCountStudentSubmissions($assignment['id']) == 0;
}
else { // Mode is add so we can always change the type of the assignment
    $canChangeType = true;
}

if (!$canChangeType) {
    echo 'cannot change';
//if ($mode == 'edit') {
    $textinput = new textinput('type');
    $textinput->size = 20;
    $textinput->value = $assignment['format'];
    $textinput->fldType = "hidden";
    $_text = $this->objLanguage->languageText('mod_assignment_cannotchangetype', 'assignment').'<br />';
    if ($assignment['format'] == '0') {
        $_text .= '<strong>'.$this->objLanguage->languageText('mod_assignment_online', 'assignment', 'Online').'</strong>';
    } else {
        $_text .= '<strong>'.$this->objLanguage->languageText('mod_assignment_upload', 'assignment', 'Upload').'</strong>';
    }
    $table->addCell($_text.$textinput->show());
//} else {
}
else {
    echo "can change and mode = $mode and format is ".$assignment['format'];
    $radio = new radio ('type');
    $radio->addOption(0, $this->objLanguage->languageText('mod_assignment_online', 'assignment', 'Online'));
    $radio->addOption(1, $this->objLanguage->languageText('mod_assignment_upload', 'assignment', 'Upload'));
    if ($mode == 'edit') {
        $radio->setSelected($assignment['format']);
        echo '&nbsp;&nbsp;&nbsp;set selected';
    }
    $radio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
    $table->addCell($radio->show());
//}
}
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_isreflection', 'assignment', 'Is it a Reflection?'));
$objRadio = new radio ('assesment_type');
$objRadio->addOption(1, $this->objLanguage->languageText('word_yes', 'system', 'Yes'));
$objRadio->addOption(0, $this->objLanguage->languageText('word_no', 'system', 'No'));
$objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');

if ($mode == 'edit') {
	$objRadio->setSelected($assignment['assesment_type']);
}
$table->addCell($objRadio->show());
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_allowresubmit', 'assignment', 'Allow Multiple Submissions'));
$radio = new radio ('resubmit');
$radio->addOption(1, $this->objLanguage->languageText('word_yes', 'system', 'Yes'));
$radio->addOption(0, $this->objLanguage->languageText('word_no', 'system', 'No'));
if ($mode == 'edit') {
    $radio->setSelected($assignment['resubmit']);
}
$radio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
$table->addCell($radio->show());
$table->endRow();




$table->startRow();
$label = new label ($this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark'), 'input_mark');
$textinput = new textinput('mark');
if ($mode == 'edit') {
    $textinput->value = $assignment['mark'];
}
$table->addCell($label->show());
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$label = new label ($this->objLanguage->languageText('mod_assignment_percentyrmark', 'assignment', 'Percentage of year mark'), 'input_yearmark');
$textinput = new textinput('yearmark');
if ($mode == 'edit') {
    $textinput->value = $assignment['percentage'];
}
$table->addCell($label->show());
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_openingdate', 'assignment', 'Opening Date'));

$objDateTime = $this->getObject('dateandtime', 'utilities');


$objDatePicker = $this->newObject('datepicker', 'htmlelements');
$objDatePicker->name = 'openingdate';
if ($mode == 'edit') {
   $objDatePicker->setDefaultDate(substr($assignment['opening_date'], 0, 10));
}

$objTimePicker = $this->newObject('timepicker', 'htmlelements');
$objTimePicker->name = 'openingtime';
if ($mode == 'edit') {
    $objTimePicker->setSelected($objDateTime->formatTime($assignment['opening_date']));
}

$s_table = $this->newObject('htmltable', 'htmlelements');
$s_table->startRow();
$s_table->addCell($objDatePicker->show(), 200);
$s_table->addCell($objTimePicker->show());
$s_table->endRow();

$table->addCell($s_table->show());
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_closingdate', 'assignment', 'Closing Date'));

$objDatePicker = $this->newObject('datepicker', 'htmlelements');
$objDatePicker->name = 'closingdate';
if ($mode == 'edit') {
    $objDatePicker->setDefaultDate(substr($assignment['closing_date'], 0, 10));
}

$objTimePicker = $this->newObject('timepicker', 'htmlelements');
$objTimePicker->name = 'closingtime';
if ($mode == 'edit') {
    $objTimePicker->setSelected($objDateTime->formatTime($assignment['closing_date']));
}

$s_table = $this->newObject('htmltable', 'htmlelements');
$s_table->startRow();
$s_table->addCell($objDatePicker->show(), 200);
$s_table->addCell($objTimePicker->show());
$s_table->endRow();

$table->addCell($s_table->show());
$table->endRow();

$objEditor = $this->newObject('htmlarea', 'htmlelements');
$objEditor->init('description', NULL, '500px', '500px');
$objEditor->setDefaultToolBarSetWithoutSave();

if ($mode == 'edit') {
    $objEditor->value = $assignment['description'];
}

$form = new form ('addeditassignment', $this->uri(array('action'=>$action)));

if ($mode == 'edit') {
    $hiddenId = new hiddeninput('id', $assignment['id']);
    $form->addToForm($hiddenId->show());
}


$form->addToForm($table->show().$objEditor->show());

$button = new button ('save', $this->objLanguage->languageText('mod_assignment_saveassignment', 'assignment', 'Save Assignment'));
$button->setToSubmit();

$form->addToForm($button->show());

$form->addRule('name', $this->objLanguage->languageText('mod_assignment_val_title', 'assignment', 'Please enter title'), 'required');
$form->addRule('mark', $this->objLanguage->languageText('mod_assignment_val_mark', 'assignment', 'Please enter mark'), 'required');
$form->addRule('mark', $this->objLanguage->languageText('mod_assignment_val_numreq', 'assignment', 'Has to be a number'), 'numeric');
$form->addRule('yearmark', $this->objLanguage->languageText('mod_assignment_val_yearmark', 'assignment', 'Please enter year mark'), 'required');
$form->addRule('yearmark', $this->objLanguage->languageText('mod_assignment_val_numreq', 'assignment', 'Has to be a number'), 'numeric');

echo $form->show();

?>
