<br />
<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');



$table = $this->newObject('htmltable', 'htmlelements');

$table->startRow();


$form = new form ('searchform', $this->uri(array('action'=>'search')));
$form->method = 'GET';

$module = new hiddeninput('module', 'realtimepresentation');
$form->addToForm($module->show());

$action = new hiddeninput('action', 'search');
$form->addToForm($action->show());

$textinput = new textinput ('q');
$textinput->size = 60;
$button = new button ('search', 'Search');
$button->setToSubmit();



$leftContents = $form->show();
// Make a tabbed box
$objTabs = $this->newObject('tabcontent', 'htmlelements');
$objTabs->width = '95%';
// Add the tag cloud to the tabbed box
$objTabs->addTab("Tag Cloud", "<span style=\"text-align:center\">" . $tagCloud . "</span>");

$leftContents .= "<span style=\"text-align:center\">" . $tagCloud . "</span>";


$objUploadedFiles = $this->getObject('dbrealtimepresentationview');
$viewTable = $objUploadedFiles->getUploadedFilesTable();

$statsTable = $this->newObject('htmltable', 'htmlelements');
$statsTable->startRow();
$statsTable->addCell($viewTable, '49%');
$statsTable->addCell('&nbsp;', '2%');

$statsTable->endRow();

$leftContents .= '<br />'.$statsTable->show();


$table->addCell($leftContents, '60%', 'top', 'left');
$table->addCell('&nbsp;&nbsp;&nbsp;', '3%');



if (count($latestFiles) == 0) {
    $latestFilesContent = '';
} else {
    $latestFilesContent = '';

    $objTrim = $this->getObject('trimstr', 'strings');

    $counter = 0;

    foreach ($latestFiles as $file)
    {
        $counter++;

        if (trim($file['title']) == '') {
            $filename = $file['filename'];
        } else {
            $filename = htmlentities($file['title']);
	        }

        $linkname = $objTrim->strTrim($filename, 45);

        $fileLink = new link ($this->uri(array('action'=>'show_presenter_applet', 'id'=>$file['id'])));
        $fileLink->link = $this->objFiles->getPresentationThumbnail($file['id']).'<br />'.$linkname;
        $fileLink->title = $filename;

        $extra = ($counter % 2 == 1) ? ' clear:both;' : '';

        $latestFilesContent .= '<div style="float: left; width: 160px; overflow: hidden; margin-right: 10px; padding-bottom: 10px;'.$extra.'">'.$fileLink->show().'</div>';
    }

    $objIcon->setIcon('rss');
    $rssLink = new link ($this->uri(array('action'=>'latestrssfeed')));
    $rssLink->link = $objIcon->show();

    $latestFilesContent .= '<br clear="left" />'.$rssLink->show();

}


$table->addCell($latestFilesContent, '60%', 'top', 'left');
$table->endRow();

echo $table->show();

$uploadbutton = new button ('upload', 'Upload');
$uploadbutton->setOnClick('document.location=\''.$this->uri(array('action'=>'show_upload_form')).'\'');

echo $uploadbutton->show();

$uploadLink = new link ($this->uri(array('action'=>'show_upload_form')));
$uploadLink->link = 'Upload Presentation';

//echo '<p>'.$uploadLink->show().'</p>';
?>

