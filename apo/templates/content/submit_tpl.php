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

$action = 'submit';
$form = new form('submitform', $this->uri(array('action' => $action, 'id' => $id)));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Submit');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();




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

//$legend = "<b>Submit</b>";
$fs = new fieldset();
//$fs->setLegend($legend);
//$fs->addContent($label->show());
//$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('submit', "Submit");
$button->setToSubmit();
$form->addToForm('<br/>'.$button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'unapproveddocs'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
