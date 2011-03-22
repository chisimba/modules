<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$objBookMarks = $this->getObject('socialbookmarking', 'utilities');
$objBookMarks->options = array('stumbleUpon', 'delicious', 'newsvine', 'reddit', 'muti', 'facebook','addThis');
$objBookMarks->includeTextLink = FALSE;

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('edit');

$heading = new htmlheading();

if ($file['filedata']['title'] == '') {
    $heading->str = $this->objLanguage->languageText("mod_podcaster_viewfile", "podcaster").' - '.$file['filedata']['title'];
} else {
    $heading->str = $file['filedata']['title'];
}


$showDeleteLink = FALSE;

if ($file['filedata']['creatorid'] == $objUser->userId()) {
    //$objSubModalWindow = $this->getObject('submodalwindow', 'htmlelements');

    //$editLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'describepodcast', 'fileid'=>$file["filedata"]['fileid'], 'mode'=>'submodal')), 'link');

    $editLink = new link ($this->uri(array('action'=>'describepodcast', 'fileid'=>$file["filedata"]['fileid'])));
    $editLink->link = $objIcon->show();
    $editLink = $editLink->show();

    $heading->str .= ' '.$editLink;
/*
    $objIcon->setIcon('delete');

    $deleteLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'delete', 'id'=>$file["filedata"]['fileid'], 'mode'=>'submodal')), 'link');

    $heading->str .= ' '.$deleteLink;
*/
    $showDeleteLink = TRUE;

}

if ($showDeleteLink == FALSE && $this->isValid('admindelete')) {
/*    $objIcon->setIcon('delete');

    $objSubModalWindow = $this->getObject('submodalwindow', 'htmlelements');
    $deleteLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'admindelete', 'id'=>$file['id'])), 'link');
    $objIcon->setIcon('delete');

    $editLink = new link ($this->uri(array('action'=>'describepodcast', 'fileid'=>$file["filedata"]['fileid'])));
    $editLink->link = $objIcon->show();
    $editLink = $editLink->show();

    $heading->str .= $editLink;*/
}

$heading->type = 1;
/*
// Check if blog is registered
$objModules  = $this->getObject('modules', 'modulecatalogue');
$blogRegistered = $objModules->checkIfRegistered('blog');

// If registered
if ($blogRegistered) {
    $objIcon->setModuleIcon('blog');
    $objIcon->title = $this->objLanguage->languageText("mod_podcaster_blogthispresentation", "podcaster");
    $objIcon->alrt =$this->objLanguage->languageText("mod_podcaster_blogthispresentation", "podcaster");

    // http://localhost/podcaster/index.php?module=blog&action=blogadmin&mode=writepost
    $blogThisLink = new link ($this->uri(array('action'=>'blogadmin', 'mode'=>'writepost', 'text'=>'[WPRESENT: id='.$file['id'].']<br /><br />'), 'blog'));
    $blogThisLink->link = $objIcon->show();

    $heading->str .= ' '.$blogThisLink->show();
}
*/
//Add RSS Link
$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('rss');

$rssLink = new link ($this->uri(array('action'=>'viewpodfeed', 'id'=>$id)));
$rssLink->link = $objIcon->show();
//Append RSS icon to the heading
$heading->str .= ' '.$rssLink->show();
echo $heading->show();

// Show the flash file using the viewer class
$objView = $this->getObject("viewer", "podcaster");

$rightCell = "";

if ($file['filedata']['description'] != '') {
    $rightCell .= '<p><strong>'
            . $this->objLanguage->languageText("word_description")
            . ':</strong><br /> '
            .nl2br($file['filedata']['description'])
            .'</p>';
}
$rightCell .=  '<p><strong>'
        . $this->objLanguage->languageText("word_tags")
        . ':</strong> ';

if (count($tags) == 0) {
    $rightCell .=  '<em>'
            . $this->objLanguage->languageText("mod_podcaster_notags", "podcaster")
            . ' </em>';
} else {
    $divider = '';
    foreach ($tags as $tag) {
        $tagLink = new link ($this->uri(array('action'=>'tag', 'tag'=>$tag['tag'])));
        $tagLink->link = $tag['tag'];
        $rightCell .=  $divider.$tagLink->show();
        $divider = ', ';
    }
}
$rightCell .=  '</p>';



$fileTypes = array('mp3'=>'mp3');

$objFileIcons = $this->getObject('fileicons', 'files');

$rightCell .= '<ul>';

foreach ($fileTypes as $fileType=>$fileName) {
    $ext = pathinfo($file['filename']);
    $ext = $ext['extension'];
    $fullPath = $this->objConfig->getcontentBasePath().'podcaster/'.$file['id'].'/'.$file['id'].'.'.$fileType;

    if (file_exists($fullPath)) {
        //$relLink = $this->objConfig->getcontentPath().'podcaster/'.$file['id'].'/'.$file['id'].'.'.$fileType;
        $link = new link($this->uri(array('action'=>'download', 'id'=>$file['id'], 'type'=>$fileType)));
        $link->link = $objFileIcons->getExtensionIcon($fileType).' '.$fileName;

        //$rightCell .= '<li>'.$link->show().'</li>';
    }

}

$rightCell .= '</ul>';

$uploaderLink = new link ($this->uri(array('action'=>'byuser', 'userid'=>$file['filedata']['creatorid'])));
$uploaderLink->link = $objUser->fullname($file['filedata']['creatorid']);

//$rightCell .= '<p><strong>'.$this->objLanguage->languageText("mod_podcaster_uploadedby", "podcaster").':</strong> '.$uploaderLink->show().'</p>';

// Output filter code for local and remote filter.
$this->loadClass('textinput','htmlelements');
$filterBox=new textinput('filter');
$filterBox->size=38;

$flashUrl = $this->uri(array('action'=>'getflash', 'id'=>$file['id']));


$fileUrl =  $this->objConfig->getsiteRoot()
        . $file['podpath'];

$rssString = "[RSS]".$fileUrl."[/RSS]";
$fileRSS = $this->parse4RSS->parse($rssString);
//$rightCell .= "<p>".$fileRSS."</p>";
/*
$filterText = "[WPRESENT: type=byurl, url=" . $fileUrl . "]";

$filterBox->setValue($filterText);

$rightCell  .= "<p><strong>" . $this->objLanguage->languageText("mod_podcaster_filterbyurl", "podcaster")
        . "</strong>: " . $filterBox->show() . "<br />"
        . $this->objLanguage->languageText("mod_podcaster_filterbyurlexplained", "podcaster")
        . "</p>";
unset($filterText);



$snippetText = '<div style="border: 1px solid #000; width: 534px; height: 402px; text-align: center;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="540" height="400">
  <param name="movie" value="' . $flashUrl . '">
  <param name="quality" value="high">
  <embed src="'.$flashUrl.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="534" height="402"></embed>
  </object></div>
';

$this->loadClass('textarea', 'htmlelements');
$snippetBox=new textarea('snippet', $snippetText, 4, 43);
$rightCell  .= "<p><strong>"
        . $this->objLanguage->languageText("mod_podcaster_snippet", "podcaster")
        . "</strong>:" . $snippetBox->show() . "<br />"
        .  $this->objLanguage->languageText("mod_podcaster_snippetexplained", "podcaster")
        . "</p>";

*/
// End of output the filter code.

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

//Get social bookmarks
$markers = $objBookMarks->show();

$leftContents= "<p>".$file['podinfo']."</p>";
$table->addCell($leftContents, 550);
$table->addCell($rightCell."<br />".$markers);
$table->endRow();
$table->startRow();

$leftContents .= '</p>';

/**
 *      * We need the agenda, so find it. If the presentation was not given any specific
 * title (which is used as default agenda), then use the file name.
 * However, the presenter will be given a chance to modify this (just temporarily
 * for the presentation) before starting a live presentation
 */


$agenda='';
if (trim($file['filedata']['title']) == '') {
    $agenda = $file['filedata']['filename'];
} else {
    $agenda = htmlentities($file['filedata']['title']);
}


//Display table
echo $table->show();


$script_src = '<script type="text/javascript" language="javascript" src="/chisimba_modules/podcaster/resources/gwt/avoir.realtime.base.gwt.Invite.nocache.js"></script>';
$this->appendArrayVar('headerParams', $script_src);

$objModule = $this->getObject('modules','modulecatalogue');
//See if the mathml module is registered and set params
$isRegistered = $objModule->checkIfRegistered('realtime');
if ($isRegistered) {
//    $sessionmanager= $this->getObject("sessionmanager", "realtime");
 //   $objTabs->addTab($this->objLanguage->languageText("mod_podcaster_livepresentation", "podcaster"), $sessionmanager->showSessionList($file['id'],$agenda,$this->objUser->fullname()));

}


$homeLink = new link ($this->uri(NULL));
$homeLink->link = $this->objLanguage->languageText("phrase_backhome");

$bottomLinks = array();

$bottomLinks[] = $homeLink->show();

echo '<p>';
$divider = '';
foreach ($bottomLinks as $link) {
    echo $divider.$link;
    $divider = ' | ';
}

echo '</p>';

?>