<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('edit');

$heading = new htmlheading();
$heading->str = 'View File - '.$file['filename'];

if ($file['creatorid'] == $objUser->userId()) {
    $editLink = new link ($this->uri(array('action'=>'edit', 'id'=>$file['id'])));
    $editLink->link = $objIcon->show();
    
    $heading->str .= ' '.$editLink->show();
}
$heading->type = 1;

echo $heading->show();

echo '<div class="noRecordsMessage" style="border:1px solid #000;">Flash Version of Presentation Goes Here</div>';

echo '<p><strong>Title of Presentation:</strong> '.$file['title'].'</p>';
echo '<p><strong>Description:</strong><br /> '.nl2br(htmlentities($file['title'])).'</p>';

echo '<p><strong>Tags:</strong> ';

if (count($tags) == 0) {
    echo '<em>Presentation has no tags yet</em>';
} else {
    $divider = '';
    foreach ($tags as $tag)
    {
        $tagLink = new link ($this->uri(array('action'=>'tag', 'tag'=>$tag['tag'])));
        $tagLink->link = $tag['tag'];
        
        echo $divider.$tagLink->show();
        $divider .= ', ';
    }
}
echo '</p>';

$objDisplayLicense = $this->getObject('displaylicense', 'creativecommons');
$objDisplayLicense->icontype = 'small';

echo '<p><strong>License:</strong> '.$objDisplayLicense->show($file['cclicense']).'</p>';

$fileTypes = array('odp', 'ppt', 'pps');

//foreach ($fileTypes as $fileType)
//{
    $ext = pathinfo($file['filename']);
    $ext = $ext['extension'];
    $fullPath = $this->objConfig->getcontentBasePath().'/webpresent/'.$file['id'].'/'.$file['id'].'.'.$ext;
    
    if (file_exists($fullPath)) {
        $relLink = $this->objConfig->getcontentPath().'/webpresent/'.$file['id'].'/'.$file['id'].'.'.$ext;
        $link = new button ('download', 'Download File');
        $link->setOnClick("document.location='".$relLink."';");
        
        echo '<h3>'.$link->show().'</h3>';
    }
    
//}


$homeLink = new link ($this->uri(NULL));
$homeLink->link = 'Back to Home';

echo '<p>'.$homeLink->show().'</p>';
?>

