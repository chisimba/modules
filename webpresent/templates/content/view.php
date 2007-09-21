<?php

// Add SlideShow if content is available
if (count($slideContent['slideshow']) > 0) {
    $this->appendArrayVar('headerParams', $this->getJavaScriptFile('slide.js'));

    $jsContent = "
    <script type=\"text/javascript\">
    <!--
      var viewer = new PhotoViewer();

    ";

    foreach ($slideContent['slideshow'] as $jsSlide)
    {
        $jsContent .= $jsSlide;
    }

    $jsContent .= "

      viewer.disableEmailLink();
      viewer.disablePhotoLink();

    //-->
    </script>";

    $this->appendArrayVar('headerParams', $jsContent);
}

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('edit');

$heading = new htmlheading();

if ($file['title'] == '') {
    $heading->str = 'View File - '.$file['filename'];
} else {
    $heading->str = $file['title'];
}

$showDeleteLink = FALSE;

if ($file['creatorid'] == $objUser->userId()) {
    $objSubModalWindow = $this->getObject('submodalwindow', 'htmlelements');

    $editLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'edit', 'id'=>$file['id'], 'mode'=>'submodal')), 'link');

    $heading->str .= ' '.$editLink;

    $objIcon->setIcon('delete');

    $deleteLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'delete', 'id'=>$file['id'], 'mode'=>'submodal')), 'link');

    $heading->str .= ' '.$deleteLink;

    $showDeleteLink = TRUE;
}

if ($showDeleteLink == FALSE && $this->isValid('admindelete')) {
    $objIcon->setIcon('delete');

    $objSubModalWindow = $this->getObject('submodalwindow', 'htmlelements');
    $deleteLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'admindelete', 'id'=>$file['id'], 'mode'=>'submodal')), 'link');

    $heading->str .= ' '.$deleteLink;
}

$heading->type = 1;

echo $heading->show();

//This should be in a reusable view class
$flashFile = $this->objConfig->getcontentBasePath().'webpresent/'.$file['id'].'/'.$file['id'].'.swf';

if (file_exists($flashFile)) {

    $flashFile = $this->objConfig->getcontentPath().'webpresent/'.$file['id'].'/'.$file['id'].'.swf';
    $flashContent = '
    <div style="border: 1px solid #000; width: 534px; height: 402px; text-align: center;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="540" height="400">

  <param name="movie" value="'.$flashFile.'">
  <param name="quality" value="high">
  <embed src="'.$flashFile.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="534" height="402"></embed>
</object></div>';
} else {
    $flashContent = '<div class="noRecordsMessage" style="border: 1px solid #000; width: 540px; height: 302px; text-align: center;">Flash Version of Presentation being converted</div>';
}




$rightCell = '';

//$rightCell = '<p><strong>Title of Presentation:</strong> '.$file['title'].'</p>';

if ($file['description'] != '') {
    $rightCell .= '<p><strong>' 
      . $this->objLanguage->languageText("word_description") 
      . ':</strong><br /> '
      .nl2br(htmlentities($file['description']))
      .'</p>';
}

$rightCell .=  '<p><strong>' 
  . $this->objLanguage->languageText("word_tags") 
  . ':</strong> ';

if (count($tags) == 0) {
    $rightCell .=  '<em>' 
    . $this->objLanguage->languageText("mod_webpresent_notags", "webpresent")
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

$objDisplayLicense = $this->getObject('displaylicense', 'creativecommons');
$objDisplayLicense->icontype = 'big';

$license = ($file['cclicense'] == '' ? 'copyright' : $file['cclicense']);

$rightCell .=  '<p>'.$objDisplayLicense->show($license).'</p>';

$rightCell .=  '<h3>' 
  . $this->objLanguage->languageText("word_download") 
  . '</h3>';

$fileTypes = array('odp'=>'OpenOffice Impress Presentation', 'ppt'=>'PowerPoint Presentation', 'pdf'=>'PDF Document');

$objFileIcons = $this->getObject('fileicons', 'files');

$rightCell .= '<ul>';

foreach ($fileTypes as $fileType=>$fileName)
{
    $ext = pathinfo($file['filename']);
    $ext = $ext['extension'];
    $fullPath = $this->objConfig->getcontentBasePath().'webpresent/'.$file['id'].'/'.$file['id'].'.'.$fileType;

    if (file_exists($fullPath)) {
        $relLink = $this->objConfig->getcontentPath().'webpresent/'.$file['id'].'/'.$file['id'].'.'.$fileType;
        $link = new link($relLink);
        $link->link = $objFileIcons->getExtensionIcon($fileType).' '.$fileName;

        $rightCell .= '<li>'.$link->show().'</li>';
    }

}

$rightCell .= '</ul>';

$uploaderLink = new link ($this->uri(array('action'=>'byuser', 'userid'=>$file['creatorid'])));
$uploaderLink->link = $objUser->fullname($file['creatorid']);

$rightCell .= '<p><strong>Uploaded by:</strong> '.$uploaderLink->show().'</p>';

// Output the filter code.
$this->loadClass('textinput','htmlelements');
$filterBox=new textinput('filter');
$filterBox->size=60;
$filterBox->setValue("[WPRESENT: id=" . $file['id'] . "]");
$rightCell  .= "<p><strong>" . $this->objLanguage->languageText("mod_webpresent_filter", "webpresent") 
  . "</strong>: " . $filterBox->show() . "<br />"
  . $this->objLanguage->languageText("mod_webpresent_filterexplained", "webpresent") 
  . "</p>";
  
 // End of output the filter code.

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$table->addCell($flashContent, 550);
$table->addCell($rightCell);

$objTabs = $this->newObject('tabcontent', 'htmlelements');

$objTabs->addTab('Presentation', $table->show());
$objTabs->addTab('Slides', $slideContent['slides']);
$objTabs->addTab('Transcript', $slideContent['transcript']);
$objTabs->width = '95%';

echo $objTabs->show();

$homeLink = new link ($this->uri(NULL));
$homeLink->link = $this->objLanguage->languageText("phrase_backhome");



echo '<p>'.$homeLink->show().'</p>';

?>
