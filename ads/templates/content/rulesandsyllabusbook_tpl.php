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
//formnumber"=>"C", "courseid"=>"math"
$form = new form ('rules', $this->uri(array('action'=>'editform',"formnumber"=>"C", "courseid"=>"math",'id'=>$this->getParam('id'),'unit_nameorg'=>$this->getParam('unit_name'),'unit_name'=>$this->getParam('unit_name'))));
$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$changetype = new textarea('b1');
$changetypeLabel = new label($this->objLanguage->languageText('mod_ads_b1', 'ads').'&nbsp;', 'change_type');
$changetype->value = $data['b1'];
if ($data['b1'] == ''||strlen($data['b1'])>255) {
    $messages[] = 'B 1 Required, or length more than 255 characters.';
}

$table->addCell($changetypeLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($changetype->show().$required);
$table->endRow();


$coursedesc = new textarea('b2');
$coursedescLabel = new label($this->objLanguage->languageText('mod_ads_b2', 'ads').'&nbsp;', 'course_desc');
$coursedesc->value = $data['b2'];
    if ($data['b2'] == ''||strlen($data['b2'])>255) {
        $messages[] = 'B 2 Required, or length more than 255 characters';
    }


$table->addCell($coursedescLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($coursedesc->show().$required);
$table->endRow();


$prereq = new textarea('b3a');
$prereqLabel = new label($this->objLanguage->languageText('mod_ads_b3a', 'ads').'&nbsp;', 'pre_req');
$prereq->value = $data['b3a'];
if (strlen($data['b3a'])>255) {
    $messages[] = 'B 3A length more than 255 characters.';
}

$table->addCell($prereqLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($prereq->show());
$table->endRow();


$coreq = new textarea('b3b');
$coreqLabel = new label($this->objLanguage->languageText('mod_ads_b3b', 'ads').'&nbsp;', 'co_req');
$coreq->value = $data['b3b'];
if (strlen($data['b3b'])>255) {
    $messages[] = 'B 3B length more than 255 characters.';
}

$table->addCell($coreqLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($coreq->show());
$table->endRow();

$b4a = new radio ('b4a');
$b4a->addOption('b4a1', $this->objLanguage->languageText('mod_ads_b4a1', 'ads'));
$b4a->addOption('b4a2', $this->objLanguage->languageText('mod_ads_b4a2', 'ads'));
$b4a->addOption('b4a3', $this->objLanguage->languageText('mod_ads_b4a3', 'ads'));
$b4a->setTableColumns(1);
if($data['b4a']=='')
$b4a->setSelected('b4a1');
else
$b4a->setSelected($data['b4a']);

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_b4a','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4a->showTable());
$table->endRow();


$b4b = new textarea('b4b');
$b4bLabel = new label($this->objLanguage->languageText('mod_ads_b4b', 'ads').'&nbsp;', 'b4_b');
$b4b->value = $data['b4b'];
if (strlen($data['b4b'])>255) {
    $messages[] = 'B 4B length more than 255 characters.';
}

$table->addCell($b4bLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4b->show());
$table->endRow();



$b4c = new textarea('b4c');
$b4cLabel = new label($this->objLanguage->languageText('mod_ads_b4c', 'ads').'&nbsp;', 'b4_c');
$b4c->value = $data['b4c'];
if (strlen($data['b4c'])>255) {
    $messages[] = 'B 4C length more than 255 characters.';
}

$table->addCell($b4cLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4c->show());
$table->endRow();

$b5a = new radio ('b5a');
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
if($data['b5a']=='')
$b5a->setSelected('b5_a1');
else
$b5a->setSelected($data['b5a']);

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_ads_b5a','ads').'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5a->showTable());
$table->endRow();


$b5b = new textarea('b5b');
$b5bLabel = new label($this->objLanguage->languageText('mod_ads_b5b', 'ads').'&nbsp;', 'b5_b');
$b5b->value = $data['b5b'];
if (strlen($data['b5b'])>255) {
    $messages[] = 'B 5B length more than 255 characters.';
}

$table->addCell($b5bLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5b->show());
$table->endRow();


/*
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
*/

$form->addToForm($table->show());

$saveButton = new button ('submitform', 'Next');
$saveButton->setToSubmit();

$buttons.=$saveButton->show();
$cancelButton = new button('cancel','Cancel');
$actionUrl = $this->uri(array('action' => NULL));
$cancelButton->setOnClick("window.location='$actionUrl'");
$buttons.='&nbsp'.$cancelButton->show();

$form->addToForm('<p align="center"><br />'.$buttons.'</p>');

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
