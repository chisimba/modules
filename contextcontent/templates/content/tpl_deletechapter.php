<?php


$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('button', 'htmlelements');



$title = $this->objLanguage->languageText('mod_contextcontent_deletechapter','contextcontent', 'Delete Chapter')." ".htmlentities($chapter['chaptertitle']);


$form = new form('deletepage', $this->uri(array('action'=>'deletechapterconfirm')));

$form->addToForm('<p><strong>'.$this->objLanguage->languageText('mod_contextcontent_deletechapterconf','contextcontent', 'Are you sure you want to delete this Chapter').'?</strong></p>');

if ($numPages > 0) {
    $warning = $this->objLanguage->languageText('mod_contextcontent_chapterhaspagesalsotobedeleted', 'contextcontent', 'Warning - This chapter has [-NUM-]  pages which will also be deleted');
    
    $warning = str_replace('[-NUM-]', $numPages, $warning);
    $form->addToForm('<p class="warning">'.$warning.'.</p>');
}

$radio = new radio ('confirmation');
$radio->addOption('N',$this->objLanguage->languageText('mod_contextcontent_delchapterconfno','contextcontent', 'No - Do not delete this chapter'));
$radio->addOption('Y',$this->objLanguage->languageText('mod_contextcontent_delchapterconfyes','contextcontent', 'Yes - Delete this chapter'));
$radio->setSelected('N');
$radio->setBreakSpace('</p><p>');

$form->addToForm('<p>'.$radio->show().'</p>');

$button = new button ('confirm', $this->objLanguage->languageText('mod_contextcontent_confirmdelcontextpages','contextcontent'));
$button->setToSubmit();

$hiddeninput = new hiddeninput('id', $id);

$form->addToForm('<p>'.$button->show().$hiddeninput->show().'</p>');

$hiddeninput = new hiddeninput('context', $this->contextCode);
$form->addToForm($hiddeninput->show().'</p>');

$hiddeninput = new hiddeninput('context', $this->contextCode);
$form->addToForm($hiddeninput->show());

$objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
echo $objHighlightLabels->show();

$featurebox = $this->newObject('featurebox', 'navigation');
echo $featurebox->show($title, $form->show());
?>