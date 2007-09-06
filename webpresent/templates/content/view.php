<?php

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

if ($file['creatorid'] == $objUser->userId()) {
    $editLink = new link ($this->uri(array('action'=>'edit', 'id'=>$file['id'])));
    $editLink->link = $objIcon->show();
    
    $heading->str .= ' '.$editLink->show();
}
$heading->type = 1;

echo $heading->show();

$flashFile = $this->objConfig->getcontentBasePath().'webpresent/'.$file['id'].'/'.$file['id'].'.swf';

if (file_exists($flashFile)) {
    
    $flashFile = $this->objConfig->getcontentPath().'webpresent/'.$file['id'].'/'.$file['id'].'.swf';
    $flashContent = '
    <div style="border: 1px solid #000; width: 540px; height: 402px; text-align: center;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="540" height="402">

  <param name="movie" value="'.$flashFile.'">
  <param name="quality" value="high">
  <embed src="'.$flashFile.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="540" height="402"></embed>
</object></div>';
} else {
    $flashContent = '<div class="noRecordsMessage" style="border: 1px solid #000; width: 540px; height: 402px; text-align: center;">Flash Version of Presentation Goes Here</div>';
}

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$table->addCell($flashContent, 550);

$rightCell = '';

//$rightCell = '<p><strong>Title of Presentation:</strong> '.$file['title'].'</p>';

if ($file['description'] != '') {
    $rightCell .= '<p><strong>Description:</strong><br /> '.nl2br(htmlentities($file['description'])).'</p>';
}

$rightCell .=  '<p><strong>Tags:</strong> ';

if (count($tags) == 0) {
    $rightCell .=  '<em>Presentation has no tags yet</em>';
} else {
    $divider = '';
    foreach ($tags as $tag)
    {
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

$rightCell .=  '<h3>Download</h3>';

$fileTypes = array('odp'=>'OpenOffice Impress Presentation', 'pps'=>'PowerPoint Presentation', 'pdf'=>'PDF Document');

$objFileIcons = $this->getObject('fileicons', 'files');

$rightCell .= '<ul>';

foreach ($fileTypes as $fileType=>$fileName)
{
    $ext = pathinfo($file['filename']);
    $ext = $ext['extension'];
    $fullPath = $this->objConfig->getcontentBasePath().'webpresent/'.$file['id'].'/'.$file['id'].'.'.$ext;
    
    if (file_exists($fullPath)) {
        $relLink = $this->objConfig->getcontentPath().'webpresent/'.$file['id'].'/'.$file['id'].'.'.$ext;
        $link = new link($relLink);
        $link->link = $objFileIcons->getExtensionIcon($fileType).' '.$fileName;
        
        $rightCell .= '<li>'.$link->show().'</li>';
    }
    
}

$rightCell .= '</ul>';

$table->addCell($rightCell);

echo $table->show();

$homeLink = new link ($this->uri(NULL));
$homeLink->link = 'Back to Home';

echo '<p>'.$homeLink->show().'</p>';
?>

