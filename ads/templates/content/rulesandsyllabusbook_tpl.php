<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 3;
$header->str = $this->objLanguage->languageText('mod_ads_section_b_rules_and_syllabus', 'ads');


$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
$form = new form ('overview', $this->uri(array('action'=>'save_overview')));
$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$coursedata=$data[0];

$changetype = new textarea('change_type');
$changetypeLabel = new label($this->objLanguage->languageText('mod_ads_b1', 'ads').'&nbsp;', 'change_type');
if ($mode == 'addfixup') {
    $unitname->value = $this->getParam('unit_name');

    if ($this->getParam('change_type') == '') {
        $messages[] = 'B 1 Required';
    }
}

$table->addCell($changetypeLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($changetype->show().$required);
$table->endRow();


$coursedesc = new textarea('course_desc');
$coursedescLabel = new label($this->objLanguage->languageText('mod_ads_b2', 'ads').'&nbsp;', 'course_desc');
if ($mode == 'addfixup') {
    $unitname->value = $this->getParam('course_desc');

    if ($this->getParam('change_type') == '') {
        $messages[] = 'B 2 Required';
    }
}

$table->addCell($coursedescLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($coursedesc->show().$required);
$table->endRow();


$prereq = new textarea('pre_req');
$prereqLabel = new label($this->objLanguage->languageText('mod_ads_b3a', 'ads').'&nbsp;', 'pre_req');
if ($mode == 'addfixup') {
    $prereq->value = $this->getParam('pre_req');

   
}

$table->addCell($prereqLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($prereq->show());
$table->endRow();


$coreq = new textarea('co_req');
$coreqLabel = new label($this->objLanguage->languageText('mod_ads_b3b', 'ads').'&nbsp;', 'co_req');
if ($mode == 'addfixup') {
    $prereq->value = $this->getParam('co_req');


}

$table->addCell($coreqLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($coreq->show());
$table->endRow();

$unitType = new radio ('unit_type');
$unitType->addOption('b4a1', $this->objLanguage->languageText('mod_ads_b4a1', 'ads'));
$unitType->addOption('b4a2', $this->objLanguage->languageText('mod_ads_b4a2', 'ads'));
$unitType->addOption('b4a3', $this->objLanguage->languageText('mod_ads_b4a3', 'ads'));
$unitType->setTableColumns(1);

if ($mode == 'addfixup') {
    $unitType->setSelected($this->getParam('unit_type'));
} else {
    $unitType->setSelected('b4a1');
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_b4a','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType->showTable());
$table->endRow();


$b4b = new textarea('b4_b');
$b4bLabel = new label($this->objLanguage->languageText('mod_ads_b4b', 'ads').'&nbsp;', 'b4_b');
if ($mode == 'addfixup') {
    $prereq->value = $this->getParam('b4_b');


}

$table->addCell($b4bLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4b->show());
$table->endRow();



$b4c = new textarea('b4_c');
$b4cLabel = new label($this->objLanguage->languageText('mod_ads_b4c', 'ads').'&nbsp;', 'b4_c');
if ($mode == 'addfixup') {
    $prereq->value = $this->getParam('b4_c');


}

$table->addCell($b4cLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4c->show());
$table->endRow();



$b5a = new radio ('b5_a');
$b5a->addOption('b5_a1', $this->objLanguage->languageText('mod_ads_b5a1', 'ads'));
$b5a->addOption('b5_a2', $this->objLanguage->languageText('mod_ads_b5a2', 'ads'));
$b5a->addOption('b5_a3', $this->objLanguage->languageText('mod_ads_b5a3', 'ads'));
$b5a->addOption('b5_a4', $this->objLanguage->languageText('mod_ads_b5a4', 'ads'));
$b5a->addOption('b5_a5', $this->objLanguage->languageText('mod_ads_b5a5', 'ads'));
$b5a->addOption('b5_a6', $this->objLanguage->languageText('mod_ads_b5a6', 'ads'));
$b5a->addOption('b5_a7', $this->objLanguage->languageText('mod_ads_b5a7', 'ads'));
$b5a->addOption('b5_a8', $this->objLanguage->languageText('mod_ads_b5a8', 'ads'));
$b5a->addOption('b5_a9', $this->objLanguage->languageText('mod_ads_b5a9', 'ads'));
$b5a->setTableColumns(1);

if ($mode == 'addfixup') {
    $b5a->setSelected($this->getParam('b5_a'));
} else {
    $b5a->setSelected('b5_a1');
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_b5a','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5a->showTable());
$table->endRow();


$b5b = new textarea('b5_b');
$b5bLabel = new label($this->objLanguage->languageText('mod_ads_b5b', 'ads').'&nbsp;', 'b5_b');
if ($mode == 'addfixup') {
    $prereq->value = $this->getParam('b5_b');


}

$table->addCell($b5bLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5b->show());
$table->endRow();



$b6a = new radio ('b6_a');
$b6a->addOption('b6_a1', $this->objLanguage->languageText('mod_ads_b6a1', 'ads'));
$b5a->addOption('b6_a2', $this->objLanguage->languageText('mod_ads_b6a2', 'ads'));
$b5a->addOption('b5_a3', $this->objLanguage->languageText('mod_ads_b5a3', 'ads'));
$b5a->addOption('b5_a4', $this->objLanguage->languageText('mod_ads_b5a4', 'ads'));
$b5a->addOption('b5_a5', $this->objLanguage->languageText('mod_ads_b5a5', 'ads'));
$b5a->addOption('b5_a6', $this->objLanguage->languageText('mod_ads_b5a6', 'ads'));
$b5a->addOption('b5_a7', $this->objLanguage->languageText('mod_ads_b5a7', 'ads'));
$b5a->addOption('b5_a8', $this->objLanguage->languageText('mod_ads_b5a8', 'ads'));
$b5a->addOption('b5_a9', $this->objLanguage->languageText('mod_ads_b5a9', 'ads'));
$b5a->setTableColumns(1);

if ($mode == 'addfixup') {
    $b5a->setSelected($this->getParam('b5_a'));
} else {
    $b5a->setSelected('b5_a1');
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_b5a','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5a->showTable());
$table->endRow();


$b5b = new textarea('b5_b');
$b5bLabel = new label($this->objLanguage->languageText('mod_ads_b5b', 'ads').'&nbsp;', 'b5_b');
if ($mode == 'addfixup') {
    $prereq->value = $this->getParam('b5_b');


}

$table->addCell($b5bLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5b->show());
$table->endRow();


$form->addToForm($table->show());

$saveButton = new button ('submitform', 'Next');
$saveButton->setToSubmit();

$buttons.=$saveButton->show();
$cancelButton = new button('cancel','Cancel');
$actionUrl = $this->uri(array('action' => NULL));
$cancelButton->setOnClick("window.location='$actionUrl'");
$buttons.='&nbsp'.$cancelButton->show();

$form->addToForm('<p align="center"><br />'.$buttons.'</p>');

if ($mode == 'addfixup') {

    foreach ($problems as $problem)
    {
        $messages[] = $this->explainProblemsInfo($problem);
    }

}

if ($mode == 'addfixup' && count($messages) > 0) {
    echo '<ul><li><span class="error">'.$this->objLanguage->languageText('mod_userdetails_infonotsavedduetoerrors', 'userdetails').'</span>';

    echo '<ul>';
        foreach ($messages as $message)
        {
            if ($message != '') {
                echo '<li class="error">'.$message.'</li>';
            }
        }

    echo '</ul></li></ul>';
}

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$leftSideColumn = $nav->getLeftContent($toSelect);
$cssLayout->setLeftColumnContent($leftSideColumn);
$rightSideColumn.='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn.='<div style="padding:10px;">'.$header->show();

//Add the table to the centered layer
$rightSideColumn .= $form->show();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();


?>
