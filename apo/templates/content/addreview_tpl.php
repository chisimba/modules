<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Section G: Review');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();


$legend = "Review";

$form = new form('reviewform');

$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('g1a');

$table->startRow();
$table->addCell('<b>G.1.a How will the course/unit syllabus be reviewed?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g1b');

$table->startRow();
$table->addCell('<b>G.1.b How often will the course/unit syllabus be reviewed?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g2a');

$table->startRow();
$table->addCell('<b>G.2.a How will integration of course/unit outcome, syllabus, teaching methods and assessment methods be evaluated?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g2b');

$table->startRow();
$table->addCell('<b>G.2.b How often will the above integration be reviewed?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g3a');

$table->startRow();
$table->addCell('<b>G.3.a How will the course/unit through-put rate be evaluated?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g3b');

$table->startRow();
$table->addCell('<b>G.3.b How often will the course/unit through-put be reviewed?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g4a');

$table->startRow();
$table->addCell('<b>G.4.a How will theteaching on the course/unit be evaluated from a students perspective and from a lectures perspective?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g4b');

$table->startRow();
$table->addCell('<b>G.4.b How often will the teaching on the course/unit be evaluated from these two perspectives?:</b>');
$table->addCell($textarea->show());
$table->endRow();

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

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$uri = $this->uri(array('action' => 'addoutcomesandassessmentthree'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm('<br/>' .$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addrulesandsyllabusone'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());


echo $form->show();

?>
