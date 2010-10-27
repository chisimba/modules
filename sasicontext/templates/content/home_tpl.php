<?php
$tab = $this->getObject('tabber', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');

$objTl = $this->getObject('tools', 'toolbar');
$bread = $this->uri(array(), "context");
$bread2 = $this->uri(array('action' => 'controlpanel'), "context");
$admin = $this->contextTitle;
$links = array('<a href="' . $bread . '">' .$admin . '</a>', '<a href="' . $bread2 . '">Control Panel</a>');
$objTl->insertBreadCrumb($links);

$objTable = new htmltable('');
//Add notification
if ($addedArray['site'] != NULL and $addedArray['context'] != NULL and $addedArray['removed'] != NULL) {
    $objTable->startRow();
    $objTable->addCell('<span id="confirm"><b>'.$addedArray['site'].'</b>'.$this->objLanguage->code2Txt("mod_sasicontext_addtosite", "sasicontext").'<b> '.$objConfig->getsiteName().'</b></span>
');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell('<span id="confirm"><b>'.$addedArray['context'].'</b>'.$this->objLanguage->code2Txt("mod_sasicontext_addtocontext", "sasicontext").'<b> '.$this->contextTitle.'</b></span>
');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell('<span id="confirm"><b>'.$addedArray['removed'].'</b>'.$this->objLanguage->code2Txt("mod_sasicontext_deleted", "sasicontext").'<b> '.$this->contextTitle.'</b></span>
');
    $objTable->endRow();
}

//Role Selection
$rad = new radio('role');
$rad->addOption('guest', 'Guest');
$rad->addOption('student', 'Student');
$rad->addOption('lecturer', 'Lecturer');
$rad->setSelected('student');
$rad->breakSpace = '&nbsp;';

$objTable->startRow();
$objTable->addCell('Add User to Role?');
$objTable->addCell($rad->showNormal());
$objTable->endRow();

//Remove user selection
$rad = new radio('remove');
$rad->addOption('y', 'Yes');
$rad->addOption('n', 'No');
$rad->setSelected('y');
$rad->breakSpace = '&nbsp;';

$objTable->startRow();
$objTable->addCell('Remove users that are not on the Class list?');
$objTable->addCell($rad->showNormal());
$objTable->endRow();

$button = new button('synchronize', 'Synchronize Users');
$button->setToSubmit();

$objTable->startRow();
$objTable->addCell($button->show());
$objTable->endRow();

$fieldset = new fieldset();
$fieldset->setLegend('Options');
$fieldset->addContent($objTable->show());

$form = new form('synchronize', $this->URI(array('action' => 'synchronize')));
$form->addToForm($fieldset->show());

$tab->tabId = TRUE;
$tab->addTab(array('name'=> 'Synchronize Users','content' => $form->show()));
echo  '<br/><center>'.$tab->show().'</center>';
?>
