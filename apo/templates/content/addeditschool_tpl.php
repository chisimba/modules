<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);

$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
if ($mode == 'edit') {
    $action = "updateschool";
}


if ($selected == '') {

    $folders = $this->__getdefaultfolder($this->baseDir);
    $selected = $folders[0];
}

$this->loadClass('dropdown', 'htmlelements');

$cfile = substr($selected, strlen($this->baseDir));

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'New Course Proposal');
if ($mode == 'edit') {
    $xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Edit Course Proposal');
}
$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

// Opening date
$table = $this->newObject('htmltable', 'htmlelements');

$textinput = new textinput('Faculty');
$textinput->size = 60;
if ($mode == 'edit') {
    $textinput->value = $schools['faculty'];
}
$table->startRow();
$table->addCell("<b>Faculty</b>");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('school');
$textinput->size = 60;
if ($mode == 'edit') {
    $textinput->value = $schools['school'];
}
$table->startRow();
$table->addCell("<b>School</b>");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('contact');
$textinput->size = 60;
if ($mode == 'edit') {
    $textinput->value = $schools['contact_person'];
} else {
    $textinput->value = $this->objUser->fullname();
}
$table->startRow();
$table->addCell("<b>Contact person</b>");
$table->addCell($textinput->show());
$table->endRow();



$textinput = new textinput('telephone');
$textinput->size = 40;
if ($mode == 'edit') {
    $textinput->value = $schools['telephone'];
}
$table->startRow();
$table->addCell("<b>Telephone number</b>");
$table->addCell($textinput->show());
$table->endRow();

$legend = "New School";
if ($mode == 'edit') {
    $legend = "Edit School";
}
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());

// Form
$form = new form('registerschool', $this->uri(array('action' => $action,'id' => $id)));



$efs = new fieldset();
$efs->setLegend('Errors');
if (count($errormessages) > 0) {

    $errorstr = '<ul>';

    foreach ($errormessages as $errormessage) {
        $errorstr.='<li class="error">' . $errormessage . '<li/>';
    }
    $errorstr.='</li>';
    $efs->addContent($errorstr);
    $form->addToForm($efs);
}
$form->addToForm($fs->show());

if($mode == 'edit') {
    $submitText = $this->objLanguage->languageText('mod_wicid_save', 'wicid', 'Update School');
}
else {
    $submitText = $this->objLanguage->languageText('mod_wicid_save', 'wicid', 'Create School');
}
$button = new button('save', $submitText);
$button->setToSubmit();

$form->addToForm('<br/>' . $button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'schoolmanagement'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>