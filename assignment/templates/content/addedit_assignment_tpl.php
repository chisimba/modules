<?php
// Can change field
if ($mode == 'edit') {
    $canChangeField = $this->objAssignmentSubmit->getCountStudentSubmissions($assignment['id']) == 0;
}
else { // Mode is add so we can always change the type of the assignment
    $canChangeField = true;
}
// Load CSS & JS
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$extbasejs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$js = '<script language="JavaScript" src="'.$this->getResourceUri('addedit_assignment.js').'" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $extbasejs);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $js);
// Ext onReady
if ($mode == 'edit') {
    $onReady = "
    Ext.onReady(function(){
        initRadioButtons(
            '".$assignment['format']."',
            '".$assignment['assesment_type']."',
            '".$assignment['resubmit']."',
            '".$assignment['email_alert']."',
            '".$assignment['filename_conversion']."',
            ".($canChangeField?'true':'false')."
        );
    });
    ";
}
else {
    $onReady = "
    Ext.onReady(function(){
        initRadioButtons(
            '0',
            '0',
            '0',
            '1',
            '1',
            true
        );
        });
    ";
}
$this->appendArrayVar('headerParams', "<script type='text/javascript'>".$onReady."</script>");
// Load classes
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
// Heading
if ($mode == 'edit') {
    $headingStr = $this->objLanguage->languageText('mod_assignment_editassignment', 'assignment', 'Edit Assignment').': '.$assignment['name'];
    $action = 'updateassignment';
} else {
    $headingStr = $this->objLanguage->languageText('mod_assignment_createassignment', 'assignment', 'Create a New Assignment');
    $action = 'saveassignment';
}
$heading = new htmlHeading();
$heading->type = 1;
$heading->str = $headingStr;
echo $heading->show();
// Table
$table = $this->newObject('htmltable', 'htmlelements');
// Name
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
// type
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_assignmenttype', 'assignment', 'Assignment Type'));
if (!$canChangeField) {
    $textinput = new textinput('type');
    $textinput->value = $assignment['format'];
    $textinput->fldType = "hidden";
    if ($assignment['format'] == '0') {
        $_type = $this->objLanguage->languageText('mod_assignment_online', 'assignment', 'Online');
    } else {
        $_type = $this->objLanguage->languageText('mod_assignment_upload', 'assignment', 'Upload');
    }
    $table->addCell($textinput->show().$_type.'<sup>1</sup>');
}
else {
    /*
    $radio = new radio ('type');
    $radio->addOption(0, $this->objLanguage->languageText('mod_assignment_online', 'assignment', 'Online'));
    $radio->addOption(1, $this->objLanguage->languageText('mod_assignment_upload', 'assignment', 'Upload'));
    if ($mode == 'edit') {
        $radio->setSelected($assignment['format']);
    }
    $radio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
    $table->addCell($radio->show());
    */
    $table->addCell('<div id="_type"></div>');
}
$table->endRow();
// Reflection
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_isreflection', 'assignment', 'Is it a Reflection?'));
/*
if (!$canChangeField) {
    $textinput = new textinput('assesment_type');
    $textinput->value = $assignment['assesment_type'];
    $textinput->fldType = "hidden";
    if ($assignment['assesment_type'] == '0') {
            $isReflection = $this->objLanguage->languageText('word_no', 'system', 'No');
        } else {
            $isReflection = $this->objLanguage->languageText('word_yes', 'system', 'Yes');
        }
    $table->addCell($textinput->show().$isReflection.'<sup>1</sup>');
}
else {
*/
/*
    $radio = new radio ('assesment_type');
    $radio->addOption(1, $this->objLanguage->languageText('word_yes', 'system', 'Yes'));
    $radio->addOption(0, $this->objLanguage->languageText('word_no', 'system', 'No'));
    $radio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
    if ($mode == 'edit') {
    	$radio->setSelected($assignment['assesment_type']);
    }
    $radio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
    $table->addCell($radio->show());
*/
$table->addCell('<div id="isReflection"></div>');
/*
}
*/
$table->endRow();
// Multiple submissions
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_allowresubmit', 'assignment', 'Allow Multiple Submissions?'));
/*
$radio = new radio ('resubmit');
$radio->addOption(1, $this->objLanguage->languageText('word_yes', 'system', 'Yes'));
$radio->addOption(0, $this->objLanguage->languageText('word_no', 'system', 'No'));
if ($mode == 'edit') {
    $radio->setSelected($assignment['resubmit']);
}
$radio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
$table->addCell($radio->show());
*/
$table->addCell('<div id="allowMultiple"></div>');
$table->endRow();


//email alerts
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_emailalert', 'assignment', 'Email Alert'));
$table->addCell('<div id="emailAlert"></div>');
$table->endRow();

//filename conversion
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_assignment_filenameconversion', 'assignment', 'Convert the Filename on Download?'));
$table->addCell('<div id="filenameConversion"></div>');
$table->endRow();

// Mark
$table->startRow();
$label = new label ($this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark'), 'input_mark');
$textinput = new textinput('mark');
if ($mode == 'edit') {
    $textinput->value = $assignment['mark'];
}
$table->addCell($label->show());
$table->addCell($textinput->show());
$table->endRow();
// Percentage of year mark
$table->startRow();
$label = new label ($this->objLanguage->languageText('mod_assignment_percentyrmark', 'assignment', 'Percentage of year mark'), 'input_yearmark');
$textinput = new textinput('yearmark');
if ($mode == 'edit') {
    $textinput->value = $assignment['percentage'];
}
$table->addCell($label->show());
$table->addCell($textinput->show());
$table->endRow();
// Opening date
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
// Closing date
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
// Description
$objEditor = $this->newObject('htmlarea', 'htmlelements');
$objEditor->init('description', NULL, '500px', '500px');
$objEditor->setDefaultToolBarSetWithoutSave();
if ($mode == 'edit') {
    $objEditor->value = $assignment['description'];
}
// Form
$form = new form ('addeditassignment', $this->uri(array('action'=>$action)));
if ($mode == 'edit') {
    $hiddenId = new hiddeninput('id', $assignment['id']);
    $form->addToForm($hiddenId->show());
}
$form->addToForm($table->show());
$form->addToForm($objEditor->show());
$button = new button ('save', $this->objLanguage->languageText('mod_assignment_saveassignment', 'assignment', 'Save Assignment'));
$button->setToSubmit();
$form->addToForm($button->show());
$form->addRule('name', $this->objLanguage->languageText('mod_assignment_val_title', 'assignment', 'Please enter title'), 'required');
$form->addRule('mark', $this->objLanguage->languageText('mod_assignment_val_mark', 'assignment', 'Please enter mark'), 'required');
$form->addRule('mark', $this->objLanguage->languageText('mod_assignment_val_numreq', 'assignment', 'Has to be a number'), 'numeric');
$form->addRule('yearmark', $this->objLanguage->languageText('mod_assignment_val_yearmark', 'assignment', 'Please enter year mark'), 'required');
$form->addRule('yearmark', $this->objLanguage->languageText('mod_assignment_val_numreq', 'assignment', 'Has to be a number'), 'numeric');
echo $form->show();
// Footer note
if (!$canChangeField) {
    echo '<sup>1</sup>'.$this->objLanguage->languageText('mod_assignment_cannotchangefield', 'assignment');
}
?>
