<style type="text/css">
.album {
    float: left;
    border: 1px solid blue;
    padding: 3px;
    text-align: center;
    width: 150px;
    text-overflow: hidden;
    margin-right: 10px;
}
.album a {display: block;}
</style>
<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

echo $this->objNewsMenu->toolbar('photos');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_photoalbums', 'news', 'Photo Albums');

echo $header->show();

if (count($albums) == 0) {
    echo '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_news_noalbumsatpresent', 'news', 'There are no albums at present').'</div>';
} else {
    foreach ($albums as $album)
    {
        echo '<div class="album">';
        
        $objThumbnails = $this->getObject('thumbnails', 'filemanager');
        $objPhotos = $this->getObject('dbnewsphotos', 'news');
        
        $firstPhoto = $objPhotos->getFirstAlbumPhoto($album['id']);
        
        $image = is_array($firstPhoto) ? '<br /><img src="'.$objThumbnails->getThumbnail($firstPhoto['fileid'], $firstPhoto['filename']).'" />' : '<br /><br />'.$this->objLanguage->languageText('mod_news_nophotos', 'news', 'No Photos').'<br />&nbsp;';
        
        $albumLink = new link ($this->uri(array('action'=>'viewalbum', 'id'=>$album['id'])));
        $albumLink->link = $album['albumname'].$image;
        
        echo '<h3>'.$albumLink->show().'</h3>';
        
        echo '</div>';
    }
}


if ($this->isValid('addalbum')) {
    $link = new link ($this->uri(array('action'=>'addalbum')));
    $link->link = $this->objLanguage->languageText('mod_news_addnewalbum', 'news', 'Add New Album');

    echo '<br clear="both" /><p>'.$link->show().'</p>';
}
?>