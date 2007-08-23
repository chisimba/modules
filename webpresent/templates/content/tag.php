<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('edit');

$heading = new htmlheading();
$heading->str = 'Tag - '.$tag;


$heading->type = 1;

echo $heading->show();

if (count($files) == 0) {
    echo '<div class="noRecordsMessage">No files matches this tag</div>';
} else {
    $table = $this->newObject('htmltable', 'htmlelements');
    
    $divider = '';
    
    $objDateTime = $this->getObject('dateandtime', 'utilities');
    $objDisplayLicense = $this->getObject('displaylicense', 'creativecommons');
    $objDisplayLicense->icontype = 'small';
    
    foreach ($files as $file)
    {
        if ($divider == 'addrow') {
            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->endRow();
        }
        
        $link = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
        $link->link = 'Image of first slide goes here';
        
        $table->startRow();
        $table->addCell('<div class="noRecordsMessage" style="border: 1px solid #000;">'.$link->show().'</div>', 150);
        $table->addCell('&nbsp;', 10);
        
        $rightContent = '';
        
        if (trim($file['title']) == '') {
            $filename = $file['filename'];
        } else {
            $filename = htmlentities($file['title']);
        }
        
        $link->link = $filename;
        $rightContent .= '<h3>'.$link->show().'</h3>';
        
        if (trim($file['description']) == '') {
            $description = '<em>File has no description</em>';
        } else {
            $description = nl2br(htmlentities($file['description']));
        }
        
        $rightContent .= '<p>'.$description.'</p>';
        
        $rightContent .= '<p><strong>License:</strong> '.$objDisplayLicense->show($file['cclicense']).'<br />';
        
        $rightContent .= '<strong>Uploaded By:</strong> '.$objUser->fullname($file['creatorid']).'<br />';
        $rightContent .= '<strong>Date Uploaded:</strong> '.$objDateTime->formatDate($file['dateuploaded']).'</p>';
        
        $table->addCell($rightContent);
        $table->endRow();
        
        $divider = 'addrow';
    }
    
    echo $table->show();
    
}

$homeLink = new link ($this->uri(NULL));
$homeLink->link = 'Back to Home';

echo '<p>'.$homeLink->show().'</p>';
?>

