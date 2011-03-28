<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass("htmltable", 'htmlelements');
$this->loadClass("textinput", "htmlelements");
$this->loadClass("dropdown", "htmlelements");


$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_podcaster_uploadstepthree', 'podcaster', 'Step 3: Describe podcast');
$header->type = 1;

echo $header->show();

$form = new form('savedescribepodcast', $this->uri(array('action' => 'savedescribepodcast', 'id' => $filedata['id'], 'fileid' => $filedata['fileid'])));
$form->extra = 'enctype="multipart/form-data"';

$objTable = new htmltable();
$objTable->width = '100%';
$objTable->attributes = " align='left' border='0'";
$objTable->cellspacing = '5';

//Title
$podtitle = new textinput("podtitle", $filedata['title']);
$podtitle->size = 60;

$objTable->startRow();
$objTable->addCell("* " . $this->objLanguage->languageText('mod_podcaster_title', 'podcaster', 'Title') . " :", 140, 'top', 'right');
$objTable->addCell($podtitle->show(), Null, 'top', 'left');
$objTable->endRow();

//Artist
$podartist = new textinput("artist", $filedata['artist']);
$podartist->size = 60;

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_podcaster_artist', 'podcaster', 'Artist') . " :", 140, 'top', 'right');
$objTable->addCell($podartist->show(), Null, 'top', 'left');
$objTable->endRow();

//Get tags
$tags = $this->objTags->getTags($filedata['id']);
$tagsStr = '';
if (count($tags) == 0) {
    $tagsStr .= '';
} else {
    $divider = '';
    foreach ($tags as $tag) {
        $tagsStr .= $divider . $tag['tag'];
        $divider = ', ';
    }
}

//Tags
$podtags = new textinput("tags", $tagsStr);
$podtags->size = 60;

$objTable->startRow();
$objTable->addCell("**" . $this->objLanguage->languageText('word_tags', 'system', 'Tags') . " :", 140, 'top', 'right');
$objTable->addCell($podtags->show(), Null, 'top', 'left');
$objTable->endRow();

//Published status
$published = new dropdown("publishstatus");
$published->addOption('0',$this->objLanguage->languageText('mod_podcaster_unpublished', 'podcaster', 'Unpublished'));
$published->addOption('1',$this->objLanguage->languageText('mod_podcaster_published', 'podcaster', 'Published'));

if(empty($filedata['publishstatus'])){
  $published->setSelected('0');
} else {
    $published->setSelected($filedata['publishstatus']);
}

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_podcaster_publishedstatus', 'podcaster', 'Published status') . " :", 140, 'top', 'right');
$objTable->addCell($published->show(), Null, 'top', 'left');
$objTable->endRow();

//Event
$podevent = new textinput("event", $filedata['event']);
$podevent->size = 60;

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_podcaster_event', 'podcaster', 'Event') . " :", 140, 'top', 'right');
$objTable->addCell($podevent->show(), Null, 'top', 'left');
$objTable->endRow();

// CC licence
$lic = $this->getObject('licensechooser', 'creativecommons');
if (isset($filedata['cclicense'])) {
    $lic->defaultValue = $filedata['cclicense'];
}
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_podcaster_license', 'podcaster', 'License') . " :", 140, 'top', 'right');
$objTable->addCell($lic->show(), Null, 'top', 'left');
$objTable->endRow();

//Add the WYSWYG editor
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->setName('description');
$editor->height = '300px';
$editor->width = '450px';
$editor->setContent($filedata['description']);

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_description', 'system', 'Description') . " :", 140, 'top', 'right');
$objTable->addCell($editor->show(), Null, 'top', 'left');
$objTable->endRow();


$buttonLabel = $this->objLanguage->languageText('word_next', 'system', 'System') . " " . $this->objLanguage->languageText('mod_podcaster_wordstep', 'podcaster', 'Step');
$buttonNote = $this->objLanguage->languageText('mod_podcaster_clicknextthree', 'podcaster', 'Click on the "Next step" button to save the descriptions and view the podcast');
$TagDesc = $this->objLanguage->languageText('mod_podcaster_tagdesc', 'podcaster', 'Separate tags by comma i.e. tag1,tag2,tag3');

//Save button
$button = new button("submit", $buttonLabel); //word_save
$button->setToSubmit();

$objTable->startRow();
$objTable->addCell(" ", 140, 'top', 'right', '', '');
//$objTable->addCell($button->show()." ".$buttonNote, 140, 'top', 'right','','colspan="2"');
$objTable->addCell("***" . $button->show(), Null, 'top', 'left');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell("* " . $this->objLanguage->languageText('mod_podcaster_notetitle', 'podcaster', 'The Title is a meaningful name of the podcast for display. However, this does not change the podcast file name'), Null, 'top', 'left', '', 'colspan="2"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell("** " . $TagDesc, Null, 'top', 'left', '', 'colspan="2"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell("*** " . $buttonNote, Null, 'top', 'left', '', 'colspan="2"');
$objTable->endRow();
$form->addToForm($objTable->show());

echo $form->show();
?>