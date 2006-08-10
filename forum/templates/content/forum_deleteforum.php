<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$Header =& $this->getObject('htmlheading', 'htmlelements');
$Header->type=1;
$Header->str=$this->objLanguage->languageText('mod_forum_deleteforum').': '.$forum['forum_name'];

echo $Header->show();

echo '<p><strong>'.$this->objLanguage->languageText('mod_forum_forumdescription').'</strong>: '.$forum['forum_description'].'</p>';

if ($forum['defaultforum'] == 'Y') {
    echo '<p class="error">'.$this->objLanguage->code2Txt('mod_forum_defaultforumcannotbedeleted').'</p>';
    echo '<p>'.$this->objLanguage->languageText('mod_forum_createanotherforumfirst').'</p>';
    
    $returnLink = new link ($this->uri(array('action'=>'administration')));
    $returnLink->link = $this->objLanguage->languageText('mod_forum_returntoforumadministration');
    
    echo '<p>'.$returnLink->show().'</p>';
} else {
    
    
    // First Cell - Deleting the Forum
    $firstCell = '<p><strong>'.$this->objLanguage->languageText('mod_forum_optiononedeleteforum').'</strong></p>';
    
    $firstCell .= '<p class="warning"><strong>'.$this->objLanguage->languageText('mod_forum_warningphrase').'</strong> '.$this->objLanguage->languageText('mod_forum_entireforumdeleted').'</p>';
    
    $firstCell .= '<p>'.$this->objLanguage->languageText('mod_forum_confirmdeleteforum').'</p>';
    
    $form1 = new form ('deleteforum', $this->uri(array('action'=>'deleteforumconfirm')));
    $hiddenInput = new hiddeninput('id', $forum['id']);
    $form1->addToForm($hiddenInput->show());
    
    
    $button = new button('deleteforum');
    $button->value = $this->objLanguage->languageText('mod_forum_confirmdeleteforumbutton');
    $button->setToSubmit();
    
    $button2 = new button ('cancel');
    $button2->value = $this->objLanguage->languageText('word_cancel');
    // fix up Cancel
    
    $form1->addToForm($button->show().' &nbsp; '.$button2->show());
    
    $firstCell .= $form1->show();
    
    // Second Cell - Making it Invisible
    $secondCell = '<p><strong>'.$this->objLanguage->languageText('mod_forum_optiontwomakeforuminvisible').'</strong></p>';
    
    $secondCell .= '<p>'.$this->objLanguage->languageText('mod_forum_preservesforumcontent').'</p>';
    
    $form2 = new form ('makeinvisible', $this->uri(array('action'=>'changevisibilityconfirm')));
    $radio = new radio ('visible');
    $radio->addOption('Y', $this->objLanguage->languageText('mod_forum_makeforumvisible'));
    $radio->addOption('N', $this->objLanguage->languageText('mod_forum_hideforum'));
    $radio->setBreakSpace(' / ');
    
    $radio->setSelected($forum['forum_visible']);
    
    $form2->addToForm('<p>'.$radio->show().'</p>');
    
    $button = new button('changevisibility');
    $button->value = $this->objLanguage->languageText('mod_forum_updateforumvisibility');
    $button->setToSubmit();
    
    $form2->addToForm('<p>'.$button->show().'</p>');
    
    $hiddenInput = new hiddeninput('id', $forum['id']);
    $form2->addToForm($hiddenInput->show());
    
    $secondCell .= $form2->show();
    
    $table = $this->newObject('htmltable', 'htmlelements');
    $table->cellpadding = 5;
    
    $table->startRow();
    $table->addCell($firstCell, '50%');
    $table->addCell($secondCell, '50%');
    $table->endRow();
    
    echo $table->show();
    
    
    $returnLink = new link ($this->uri(array('action'=>'administration')));
    $returnLink->link = $this->objLanguage->languageText('mod_forum_returntoforumadministration');
    
    echo '<p align="center">'.$returnLink->show().'</p>';
}
?>