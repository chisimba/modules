<?php
//$this->setVar('pageSuppressXML',true);
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$header = new htmlheading();
if ($mode=='edit') {
    $header->str = $this->objLanguage->languageText('mod_contextcontent_editcontextpages','contextcontent').': '.htmlentities($page['menutitle']);
    $this->setVar('pageTitle', htmlentities($this->objContext->getTitle().' - '.$this->objLanguage->languageText('mod_contextcontent_editcontextpages','contextcontent').': '.$page['menutitle']));
} else {
    $header->str = $this->objLanguage->languageText('mod_contextcontent_addnewcontextpages','contextcontent');
    $this->setVar('pageTitle', htmlentities($this->objContext->getTitle().' - '.$this->objLanguage->languageText('mod_contextcontent_addnewcontextpages','contextcontent')));
}
$header->type = 1;
echo $header->show();

$form = new form('addpage', $this->uri(array('action'=>$formaction)));
$formTable = $this->newObject('htmltable', 'htmlelements');

//if ($mode=='add') {
    $label = new label ($this->objLanguage->languageText('mod_contextcontent_parent','contextcontent'), 'input_parentnode');

    $formTable->startRow();
    $formTable->addCell($label->show());
    $formTable->addCell($tree);
    $formTable->endRow();
//}
$menuTitle = new textinput('menutitle');
$menuTitle->size = '80%';

if ($mode=='edit') {
    $menuTitle->value = htmlentities($page['menutitle']);
}

$label = new label ($this->objLanguage->languageText('mod_contextcontent_pagetitle','contextcontent'), 'input_menutitle');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($menuTitle->show());
$formTable->endRow();

$htmlarea = $this->newObject('htmlarea', 'htmlelements');
$htmlarea->setName('pagecontent');
if ($mode == 'add') {
    $htmlarea->setContent('<h1>'.$this->objLanguage->languageText('mod_contextcontent_addtitle','contextcontent').'</h1>'.'<p>'.$this->objLanguage->languageText('mod_contextcontent_startcontent','contextcontent').'</p>');
} else {
    $htmlarea->setContent($page['pagecontent']);
}

$label = new label ($this->objLanguage->languageText('mod_contextcontent_pagecontent','contextcontent'), 'input_htmlarea');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($htmlarea->showFCKEditor());
$formTable->endRow();

$textarea = new textarea('headerscripts');
$textarea->extra = ' style="width: 100%"';
$textarea->rows = 10;
if ($mode=='edit') {
    $textarea->value = htmlentities($page['headerscripts']);
}
$label = new label ('<strong>'.$this->objLanguage->languageText('mod_contextcontent_metatags_javascript','contextcontent').'</strong>', 'input_headerscripts');

$formTable->startRow();
$formTable->addCell($label->show().'<p>'.$this->objLanguage->languageText('mod_contextcontent_enterjavascript','contextcontent').'</p>', '240');
$formTable->addCell($textarea->show());
$formTable->endRow();

// $languageList = new dropdown('language');
// $languageCodes = & $this->getObject('languagecode','language');
// asort($languageCodes->iso_639_2_tags->codes); 
// foreach ($languageCodes->iso_639_2_tags->codes as $key => $value) {
    // $languageList->addOption($key, $value);
// }
// $languageList->setSelected($languageCodes->getISO($this->objLanguage->currentLanguage()));
// $label = new label ('Page Language', 'input_language');
// $formTable->startRow();
// $formTable->addCell($label->show());
// $formTable->addCell($languageList->show());
// $formTable->endRow();

$formTable->startRow();
$formTable->addCell('&nbsp;');
$formTable->addCell('&nbsp;');
$formTable->endRow();

$button = new button('submitform', $this->objLanguage->languageText('mod_contextcontent_savepage','contextcontent'));
$button->setToSubmit();

$formTable->startRow();
$formTable->addCell('&nbsp;');
$formTable->addCell($button->show());
$formTable->endRow();

$form->addToForm($formTable->show());

if ($mode == 'edit') {
    $hiddeninput = new hiddeninput('id', $page['id']);
    $form->addToForm($hiddeninput->show());
    $hiddeninput = new hiddeninput('context', $this->contextCode);
    $form->addToForm($hiddeninput->show());
} else {
    $hiddeninput = new hiddeninput('chapter', $chapter);
    $form->addToForm($hiddeninput->show());

}

// Rules

$form->addRule('menutitle', $this->objLanguage->languageText('mod_contextcontent_pleaseenterpagetitle','contextcontent'), 'required');

echo $form->show();

?>