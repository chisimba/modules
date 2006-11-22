<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');

echo '<h1>'.$this->objLanguage->languageText('mod_podcast_addpodcast', 'podcast').'</h1>';

$form = new form('addpodcast', $this->uri(array('action'=>'savenewpodcast')));


$objSelectFile = $this->newObject('selectfile', 'filemanager');

$objSelectFile->name = 'podcast';
$objSelectFile->restrictFileList = array('mp3');

$button = new button('submitform', $this->objLanguage->languageText('mod_podcast_addpodcast', 'podcast'));
$button->setToSubmit();;

$form->addToForm($objSelectFile->show().'<br />'.$button->show());


echo $form->show();

$link = new link ($this->uri(NULL));
$link->link = $this->objLanguage->languageText('mod_podcast_returntopodcasthome', 'podcast');

echo '<p>'.$link->show().'</p>';
?>