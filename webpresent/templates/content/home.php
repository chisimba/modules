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

echo '<p align="center">'.$tagCloud.'</p>';


$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

if (count($latestFiles) == 0) {
    $latestFilesContent = 'asfasf';
} else {
    $latestFilesContent = '<ul>';
    
    foreach ($latestFiles as $file)
    {
        if (trim($file['title']) == '') {
            $filename = $file['filename'];
        } else {
            $filename = htmlentities($file['title']);
        }
        
        $fileLink = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
        $fileLink->link = $filename;
        
        $latestFilesContent .= '<li>'.$fileLink->show().'</li>';
    }
    
    $latestFilesContent .= '</ul>';
}
$table->addCell('<h3>Top 10 Quoted Presentations</h3>'.$latestFilesContent, '50%');
$table->addCell('<h3>Top 10 Quoted Presentations</h3><div class="noRecordsMessage">Under Construction</div>', '50%');
$table->endRow();

echo $table->show();


$uploadbutton = new button ('upload', 'Upload');
$uploadbutton->setOnClick('document.location=\''.$this->uri(array('action'=>'upload')).'\'');

//echo $uploadbutton->show();

$uploadLink = new link ($this->uri(array('action'=>'upload')));
$uploadLink->link = 'Upload Presentation';

echo '<p>'.$uploadLink->show().'</p>';
?>

