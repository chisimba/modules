<?php

$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->objformdata = $this->getObject('dbformdata');
$this->setVar('pageSuppressXML', TRUE);
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$id=$this->getParam('id');

$doctitle = "doc title";
$docauthor = "doc author";
$docrefno = "doc ref no";

$action = 'submit';
$form = new form('submitform', $this->uri(array('action' => $action, 'id' => $id)));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Submit');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '50%';
$table->cellpadding = '2';
$table->cellspacing = '3';

$table->startRow();
$table->addCell("Title: ");
$table->addCell($doctitle);
$table->endRow();

$table->startRow();
$table->addCell("Author: ");
$table->addCell($docauthor);
$table->endRow();

$table->startRow();
$table->addCell("Reference no: ");
$table->addCell($docrefno);
$table->endRow();

/*        submitCombo.add("Creator");
        submitCombo.add("APO");
        submitCombo.add("SubFaculty");
        submitCombo.add("Faculty");
        submitCombo.add("Senate");*/

$dropdown = new dropdown('submitto');
//$dropdown->addOption('');
$dropdown->addOption("Creator");
$dropdown->addOption("APO");
$dropdown->addOption("Subfaculty");
$dropdown->addOption("Faculty");
$dropdown->addOption("Senate");

$table->startRow();
$table->addCell("Submit to: ");
$table->addCell($dropdown->show());
$table->endRow();

$legend = "<b>Submit</b>";
$fs = new fieldset();
$fs->width = 700;
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());


/*$efs = new fieldset();
$efs->setLegend('Errors');
if (count($errormessages) > 0) {

    $errorstr = '<ul>';

    foreach ($errormessages as $errormessage) {
        $errorstr.='<li class="error">' . $errormessage ;//. '<li/>';
    }
    $errorstr.='</li>';
    $efs->addContent($errorstr);
    $form->addToForm($efs);
}*/

$button = new button('submit', "Submit");
$button->setToSubmit();
$form->addToForm('<br/>'.$button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'unapproveddocs'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
