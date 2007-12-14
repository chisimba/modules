<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$this->appendArrayVar('headerParams', $this->getJavaScriptFile('frog.js'));

$objIcon = $this->newObject('geticon', 'htmlelements');

$links = array();

$backToAlbums = new link ($this->uri(array('action'=>'photoalbums')));
$backToAlbums->link = $this->objLanguage->languageText('mod_news_backtoalbums', 'news', 'Back to Albums');
$links[] = $backToAlbums->show();

if ($this->isValid('editalbumphotos')) {
    $addPhotos = new link ($this->uri(array('action'=>'editalbumphotos', 'id'=>$album['id'])));
    $addPhotos->link = $this->objLanguage->languageText('mod_news_addphotos', 'news', 'Add Photos');
    $links[] = $addPhotos->show();
}

if ($this->isValid('editalbum')) {
    $albumSettings = new link ($this->uri(array('action'=>'editalbum', 'id'=>$album['id'])));
    $albumSettings->link = $this->objLanguage->languageText('word_settings', 'news', 'Settings');
    $links[] = $albumSettings->show();
}

if ($this->isValid('photocaptions')) {
    $manageCaptions = new link ($this->uri(array('action'=>'photocaptions', 'id'=>$album['id'])));
    $manageCaptions->link = $this->objLanguage->languageText('word_captions', 'news', 'Captions');
    $links[] = $manageCaptions->show();
}

if ($this->isValid('deletealbum')) {
    $manageCaptions = new link ($this->uri(array('action'=>'deletealbum', 'id'=>$album['id'])));
    $manageCaptions->link = $this->objLanguage->languageText('mod_news_deletealbum', 'news', 'Delete Album');
    $links[] = $manageCaptions->show();
}







echo $this->objNewsMenu->toolbar('photos');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_photoalbum', 'news', 'Photo Album').':'.$album['albumname'];

echo $header->show();


if (count($albumPhotos) == 0) {
    echo '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_news_albumhasnophotos', 'news', 'Album has no photos').'. '.$addPhotos->show().'</div>';
} else {

    $objThumbnails = $this->getObject('thumbnails', 'filemanager');
    
    echo '<div id="FrogJS">';
        $counter = 1;
        $images = array();
        
        foreach ($albumPhotos as $photo)
        {
            $thumbnail = '<img src="'.$objThumbnails->getThumbnail($photo['fileid'], 'photo.jpg').'" alt="'.$counter.' / '.count($albumPhotos).' - '.htmlentities($photo['caption']).'" />';
            
            $images[] = $this->uri(array('action'=>'file', 'id'=>$photo['fileid'], 'filename'=>$photo['filename']), 'filemanager');
            
            echo '<a href="'.$this->uri(array('action'=>'file', 'id'=>$photo['fileid'], 'filename'=>$photo['filename']), 'filemanager').'">
                '.$thumbnail.'
            </a>';
            
            $counter++;
        }
	echo '</div>';
    
    
    echo '<div style="height: 0px; display: none;">';
    foreach ($images as $image)
    {
        echo '<img src="'.$image.'" />';;
    }
    echo '</div>';
    
}


if (count($links) > 0) {
    echo '<p>';
    $divider = '';
    foreach ($links as $link)
    {
        echo $divider.$link;
        $divider = ' : ';
    }
    echo '</p>';
}
?>
<style type="text/css">
    #FrogJS{
        width: 700px;
        height: 350px;
        margin: 0 auto;
    }
    #FrogJSCredit{
        text-align: right;
        font-size: 80%;
        color: #999;
        padding: 1px;
    }
    #FrogJSCaption{
        text-align: center;
        line-height: 140%;
    }
</style>