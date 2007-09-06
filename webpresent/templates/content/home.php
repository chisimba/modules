<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$form = new form ('searchform', $this->uri(array('action'=>'search')));
$textinput = new textinput ('query');
$textinput->size = 60;
$button = new button ('search', 'Search');
$button->setOnClick("alert('I dont work yet');");



$form->addToForm($textinput->show().' '.$button->show());

echo $form->show();

$table = $this->newObject('htmltable', 'htmlelements');

$table->startRow();

$table->addCell($tagCloud, '60%', 'top', 'center');



if (count($latestFiles) == 0) {
    $latestFilesContent = '';
} else {
    $latestFilesContent = '';
    
    $objTrim = $this->getObject('trimstr', 'strings');
    
    foreach ($latestFiles as $file)
    {
        if (trim($file['title']) == '') {
            $filename = $file['filename'];
        } else {
            $filename = htmlentities($file['title']);
        }
        
        $linkname = $objTrim->strTrim($filename, 45);
        
        $fileLink = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
        $fileLink->link = $this->objFiles->getPresentationThumbnail($file['id']).'<br />'.$linkname;
        $fileLink->title = $filename;
        
        $latestFilesContent .= '<div style="float: left; width: 160px; overflow: hidden; margin-right: 10px; padding-bottom: 10px;">'.$fileLink->show().'</div>';
    }
    

}
$table->addCell('<h3>10 Newest Uploads:</h3>'.$latestFilesContent, '40%');

$table->endRow();

echo $table->show();


$uploadbutton = new button ('upload', 'Upload');
$uploadbutton->setOnClick('document.location=\''.$this->uri(array('action'=>'upload')).'\'');

//echo $uploadbutton->show();

$uploadLink = new link ($this->uri(array('action'=>'upload')));
$uploadLink->link = 'Upload Presentation';

//echo '<p>'.$uploadLink->show().'</p>';
?>

