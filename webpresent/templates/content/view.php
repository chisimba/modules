f<?php
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

/*
// Add SlideShow if content is available
if (count($slideContent['slideshow']) > 0) {
    $this->appendArrayVar('headerParams', $this->getJavaScriptFile('smoothgalleryslightbox/mootools.js'));
    $this->appendArrayVar('headerParams', $this->getJavaScriptFile('smoothgalleryslightbox/jd.gallery.js'));
    $this->appendArrayVar('headerParams', $this->getJavaScriptFile('smoothgalleryslightbox/slightbox.js'));


    $this->appendArrayVar('headerParams', '<link rel="stylesheet" href="'.$this->getResourceUri('smoothgalleryslightbox/jd.gallery.css').'" type="text/css" media="screen" charset="utf-8" />');
    $this->appendArrayVar('headerParams', '<link rel="stylesheet" href="'.$this->getResourceUri('smoothgalleryslightbox/slightbox.css').'" type="text/css" media="screen" charset="utf-8" />');

    $jsContent = '';

    $this->appendArrayVar('headerParams', $jsContent);
    $this->appendArrayVar('bodyOnLoad', 'var mylightbox = new Lightbox();');
}

*/
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$objBookMarks = $this->getObject('socialbookmarking', 'utilities');
$objBookMarks->includeTextLink = FALSE;

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

    //$editLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'edit', 'id'=>$file['id'], 'mode'=>'submodal')), 'link');

    //$heading->str .= ' '.$editLink;

    $objIcon->setIcon('delete');

    //$deleteLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'delete', 'id'=>$file['id'], 'mode'=>'submodal')), 'link');

    //$heading->str .= ' '.$deleteLink;

    $showDeleteLink = TRUE;
}

if ($showDeleteLink == FALSE && $this->isValid('admindelete')) {
    $objIcon->setIcon('delete');

    $objSubModalWindow = $this->getObject('submodalwindow', 'htmlelements');
    $deleteLink = $objSubModalWindow->show($objIcon->show(), $this->uri(array('action'=>'admindelete', 'id'=>$file['id'], 'mode'=>'submodal')), 'link');

    $heading->str .= ' '.$deleteLink;
}

$heading->type = 1;

$objIcon->setModuleIcon('blog');
$objIcon->title = 'Blog This';
$objIcon->alrt = 'Blog This';

// http://localhost/webpresent/index.php?module=blog&action=blogadmin&mode=writepost
$blogThisLink = new link ($this->uri(array('action'=>'blogadmin', 'mode'=>'writepost', 'text'=>'[WPRESENT: id='.$file['id'].']<br /><br />'), 'blog'));
$blogThisLink->link = $objIcon->show();

$heading->str .= ' '.$blogThisLink->show();

echo $heading->show();

// Show the flash file using the viewer class
$objView = $this->getObject("viewer", "webpresent");
$flashContent = $objView->showFlash($file['id']);




$rightCell = '<div style="float:right">'.$objBookMarks->diggThis().'</div>';



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
        //$relLink = $this->objConfig->getcontentPath().'webpresent/'.$file['id'].'/'.$file['id'].'.'.$fileType;
        $link = new link($this->uri(array('action'=>'download', 'id'=>$file['id'], 'type'=>$fileType)));
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


$leftContents = $flashContent;



$leftContents .= '<br /><p>'.$objBookMarks->addThis();
$divider = ' &nbsp;';

foreach ($objBookMarks->options as $option)
{
    if ($option != 'diggThis' && $option != 'addThis') {
        $leftContents .= $divider.$objBookMarks->$option();
    }
}

$leftContents .= '</p>';

$table->addCell($leftContents, 550);
$table->addCell($rightCell);

$objTabs = $this->newObject('tabcontent', 'htmlelements');

/**
 * Following added by david wafula
 */

$scheduleLink = new link ($this->uri(array('action'=>'schedule', 'id'=>$file['id'],'title'=>$file['title'],'filename'=>$file['filename'])));
$scheduleLink->link = 'Schedule for Live Presentation';

$presenterLink = new link ($this->uri(array('action'=>'showpresenterapplet', 'id'=>$file['id'])));
$presenterLink->link = 'Start Live Presentation';



$objTabs->addTab('Presentation', $table->show());
$objTabs->addTab('Slides', $slideContent['slides']);
$objTabs->addTab('Transcript', $slideContent['transcript']);

if ($file['creatorid'] == $objUser->userId()) {
$objTabs->addTab('Live', '<li>'.$scheduleLink->show().'</li><li>'.$presenterLink->show().'</li>');

}

$objTabs->width = '95%';

echo $objTabs->show();

$homeLink = new link ($this->uri(NULL));
$homeLink->link = $this->objLanguage->languageText("phrase_backhome");

$bottomLinks = array();

$bottomLinks[] = $homeLink->show();

if ($this->isValid('regenerate'))
{
    $flashLink = new link ($this->uri(array('action'=>'regenerate', 'type'=>'flash', 'id'=>$file['id'])));
    $flashLink->link = 'Regenerate Flash';
    $bottomLinks[] = $flashLink->show();

    $slidesLink = new link ($this->uri(array('action'=>'regenerate', 'type'=>'slides', 'id'=>$file['id'])));
    $slidesLink->link = 'Slides';
    $bottomLinks[] = $slidesLink->show();

    $pdfLink = new link ($this->uri(array('action'=>'regenerate', 'type'=>'pdf', 'id'=>$file['id'])));
    $pdfLink->link = 'PDF';
    $bottomLinks[] = $pdfLink->show();


}

$blogThisLink = new link ($this->uri(array('action'=>'blogadmin', 'mode'=>'writepost', 'text'=>'[WPRESENT: id='.$file['id'].']<br /><br />'), 'blog'));
$blogThisLink->link = 'BlogThis Presentation';

$bottomLinks[] = $blogThisLink->show();


echo '<p>';
$divider = '';
foreach ($bottomLinks as $link)
{
    echo $divider.$link;
    $divider = ' | ';
}

echo '</p>';

?>