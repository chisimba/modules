<?php

/*
 <p>Todo:</p>
<ul>
<li>Allow photos to be removed from here</li>
<li>Fix - change order of photos</li>
</ul>
*/

$this->loadClass('form', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$albumLink = new link ($this->uri(array('action'=>'viewalbum', 'id'=>$album['id'])));
$albumLink->link = $album['albumname'];

$addPhotos = new link ($this->uri(array('action'=>'editalbumphotos', 'id'=>$album['id'])));
$addPhotos->link = $this->objLanguage->languageText('mod_news_addphotos', 'news', 'Add Photos');

$albumSettings = new link ($this->uri(array('action'=>'editalbum', 'id'=>$album['id'])));
$albumSettings->link = $this->objLanguage->languageText('word_settings', 'news', 'Settings');

echo $this->objNewsMenu->toolbar('photos');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_photocaptions', 'news', 'Photo Captions').': '.$albumLink->show().' ('.$albumSettings->show().' / '.$addPhotos->show().')';

echo $header->show();

if (count($albumPhotos) == 0) {
    echo '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_news_albumhasnophotos', 'news', 'Album has no photos').'. '.$addPhotos->show().'</div>';
} else {
    
    $objThumbnails = $this->getObject('thumbnails', 'filemanager');
    $form = new form ('savecaptions', $this->uri(array('action'=>'savecaptions')));
    $table = $this->newObject('htmltable', 'htmlelements');
    
    foreach ($albumPhotos as $photo)
    {
        $table->startRow();
        $image = '<img src="'.$objThumbnails->getThumbnail($photo['fileid'], 'photo.jpg').'" />';
        $table->addCell($image);
        
        $textarea = new textarea($photo['id']);
        $textarea->value = $photo['caption'];
        $table->addCell($textarea->show());
        
        $table->endRow();
        
    }
    
    $table->startRow();
    $table->addCell('&nbsp;');
    $table->addCell('&nbsp;');
    $table->endRow();
    
    $button = new button ('savecaptions', $this->objLanguage->languageText('mod_news_savecaptions', 'news', 'Save Captions'));
    $button->setToSubmit();
    
    $table->startRow();
    $table->addCell('&nbsp;');
    $table->addCell('<p>'.$button->show().'</p>');
    $table->endRow();
    
    $form->addToForm($table->show());
    
    $hiddeninput = new hiddeninput('id', $album['id']);
    $form->addToForm($hiddeninput->show());
    
    echo $form->show();
}

?>
