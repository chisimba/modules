<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');

$albumLink = new link ($this->uri(array('action'=>'viewalbum', 'id'=>$album['id'])));
$albumLink->link = $album['albumname'];

$manageCaptions = new link ($this->uri(array('action'=>'photocaptions', 'id'=>$album['id'])));
$manageCaptions->link = $this->objLanguage->languageText('word_captions', 'word', 'Captions');;

$albumSettings = new link ($this->uri(array('action'=>'editalbum', 'id'=>$album['id'])));
$albumSettings->link = $this->objLanguage->languageText('word_settings', 'word', 'Settings');

echo $this->objNewsMenu->toolbar('photos');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_managealbumphotos', 'news', 'Manage Album Photos').': '.$albumLink->show().' ('.$albumSettings->show().' / '.$manageCaptions->show().')';

echo $header->show();

//echo '<p>@todo: Add Upload Form</p>';
//echo '<p>@todo: Photo Order</p>';


if (count($albumPhotos) == 0) {
    echo '<div id="photos"><div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_news_nophotosinalbum', 'news', 'No Photos in Album').'</div></div>';
} else {
    echo '<div id="photos">';
    
    $objThumbnails = $this->getObject('thumbnails', 'filemanager');
    
    foreach ($albumPhotos as $photo)
    {
        echo '<div id="delete_'.$photo['fileid'].'" class="photoimage">';
        echo '<a href="javascript:removePhoto(\''.$photo['fileid'].'\');">'.$this->objLanguage->languageText('mod_news_removephoto', 'news', 'Remove Photo').'</a>';
        
        echo '<div id="contents_'.$photo['fileid'].'">';
        echo '<img src="'.$objThumbnails->getThumbnail($photo['fileid'], 'file.jpg').'" />';
        echo '</div></div>';
    }
    
    echo '</div>';
}



echo '<br clear="all" />';

$objIcon->setIcon('loader');
echo '<div id="savemessage">'.$objIcon->show().' '.$this->objLanguage->languageText('word_saving', 'word', 'Saving').'... </div>';

echo '<h3>'.$this->objLanguage->languageText('mod_word_browseforphoto', 'word', 'Browse for Photo').'</h3>';

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$filemanagerLink = new link ($this->uri(array(NULL), 'filemanager'));
$filemanagerLink->link = $this->objLanguage->languageText('mod_filemanager_name', 'filemanager', 'File Manager');

$table->addCell($folders .'<br /><br /><br /><p>'.$filemanagerLink->show().'</p>', '25%');
$table->addCell('<div id="loadingfiles">'.$objIcon->show().' '.$this->objLanguage->languageText('phrase_loadingfiles', 'phrase', 'Loading Files').' ... </div>'.'<div id="folderphotos"></div>');
$table->endRow();

echo $table->show();





$albumLink = new link ($this->uri(array('action'=>'viewalbum', 'id'=>$album['id'])));
$albumLink->link = $this->objLanguage->languageText('mod_news_returntoalbum', 'news', 'Return to Album');

$returnLink = new link ($this->uri(array('action'=>'photoalbums')));
$returnLink->link = $this->objLanguage->languageText('mod_news_returntoallalbums', 'news', 'Return to ALL Albums');

echo '<br /><p>'.$albumLink->show().' / '.$returnLink->show().'</p>';


$this->appendArrayVar('bodyOnLoad', 'loadFolder(\'root\');');
?>
<script type="text/javascript">
//<![CDATA[

$('savemessage').style.visibility='hidden';
$('loadingfiles').style.display='none';

var albumid='<?php echo $album['id'];?>';

var currentfolder = 'asdad';

function loadFolder(id) {
	var url = 'index.php';
	var pars = 'module=news&action=loadfolderimages&id='+id+'&albumid='+albumid;
    
    // Done to prevent refreshing of current folder
    if (id == currentfolder) {
        oktorequest = false;
    } else {
        currentfolder = id;
        oktorequest = true;
    }
    
    if (oktorequest) {
        var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onLoading: showLoadingFiles, onComplete: showResponse} );
    }
}

function showResponse (originalRequest) {
	var newData = originalRequest.responseText;
	$('folderphotos').innerHTML = newData;
    hideLoadingFiles();
    adjustLayout();
    
    //alert(currentfolder);
}

function showLoadingFiles()
{
    $('loadingfiles').style.display='block';
}

function hideLoadingFiles()
{
    $('loadingfiles').style.display='none';
}

function showSaving()
{
    $('savemessage').style.visibility='visible';
}

function hideSaving()
{
    $('savemessage').style.visibility='hidden';
    adjustLayout();
}

function addPhoto(id)
{
    addcontents = '<div id="delete_'+id+'" class="photoimage">';
    addcontents +='<a href="javascript:removePhoto(\''+id+'\');"><?php echo addslashes($this->objLanguage->languageText('mod_news_removephoto', 'news', 'Remove Photo')); ?></a>';
    
    addcontents += '<div id="contents_'+id+'">';
    addcontents += $('contents_'+id).innerHTML;
    addcontents += '</div></div>';
    
    $('add_'+id).remove();
    
    if ($('photos').innerHTML == '<div class="noRecordsMessage"><?php echo addslashes($this->objLanguage->languageText('mod_news_nophotosinalbum', 'news', 'No Photos in Album')); ?></div>') {
        $('photos').innerHTML = addcontents;
    } else {
        $('photos').innerHTML += addcontents;
    }
    
    var url = 'index.php';
	var pars = 'module=news&action=addphototoalbum&id='+id+'&album='+albumid;
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onLoading: showSaving,onComplete: hideSaving} );
}

function removePhoto(id)
{
    addcontents = '<div id="add_'+id+'" class="photoimage">';
    addcontents +='<a href="javascript:addPhoto(\''+id+'\');"><?php echo addslashes($this->objLanguage->languageText('mod_news_addphoto', 'news', 'Add Photo')); ?></a>';
    
    addcontents += '<div id="contents_'+id+'">';
    addcontents += $('contents_'+id).innerHTML;
    addcontents += '</div></div>';
    
    $('delete_'+id).remove();
    
    $('folderphotos').innerHTML += addcontents;
    
    if ($('photos').empty()) {
        $('photos').innerHTML = '<div class="noRecordsMessage"><?php echo addslashes($this->objLanguage->languageText('mod_news_nophotosinalbum', 'news', 'No Photos in Album')); ?></div>';
    }
    
    var url = 'index.php';
	var pars = 'module=news&action=removephotofromalbum&id='+id+'&album='+albumid;
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onLoading: showSaving,onComplete: hideSaving} );
}

//]]>

</script>
<style type="text/css">
.photoimage {
    float: left; width: 120px; height: 120px;
}
</style>