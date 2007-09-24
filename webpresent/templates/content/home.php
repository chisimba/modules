<br />
<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');

$form = new form ('searchform', $this->uri(array('action'=>'search')));
$form->method = 'GET';

$module = new hiddeninput('module', 'webpresent');
$form->addToForm($module->show());

$action = new hiddeninput('action', 'search');
$form->addToForm($action->show());

$textinput = new textinput ('q');
$textinput->size = 60;
$button = new button ('search', 'Search');
$button->setToSubmit();

$form->addToForm($textinput->show().' '.$button->show());

// Turn off so long
//echo $form->show();

$table = $this->newObject('htmltable', 'htmlelements');

$table->startRow();

//--------- ADDED BY DEREK FOR EMAIL
// Add the tag cloud to the left contents.
$leftContents = "";
// Make a tabbed box
$objTabs = $this->newObject('tabcontent', 'htmlelements');
$objTabs->width = '95%';
// Add the tag cloud to the tabbed box
$objTabs->addTab("Tag Cloud", "<span style=\"text-align:center\">" . $tagCloud . "</span>");

if ($this->objUser->isLoggedIn()) {

    // Counter for additional modules that may be registered
    $moduleCounter = 0;

    $objModule = $this->getObject('modules','modulecatalogue');
    //See if the youtube API module is registered and set a param
    $emailRegistered = $objModule->checkIfRegistered('email', 'email');
    if ($emailRegistered) {
        $moduleCounter++;
        //Add the email messages to the tabbed box
        $msgs = $this->getObject("messagestpl", "webpresent");
        $msgList = $msgs->show();
        $msgTitle = $this->objLanguage->languageText("mod_webpresent_msgs", "webpresent")
          .  $msgs->msgCount;
        $objTabs->addTab($msgTitle, $msgList);
    }

    $objModule = $this->getObject('modules','modulecatalogue');
    //See if the youtube API module is registered and set a param
    $buddiesRegistered = $objModule->checkIfRegistered('buddies', 'buddies');
    if ($buddiesRegistered) {
        $moduleCounter++;
        //Add the email messages to the tabbed box
        $buds = $this->getObject("buddiestpl", "webpresent");
        $budList = $buds->show();
        // Add buddies to the tabbed box
        $objTabs->addTab($this->objLanguage->languageText("mod_webpresent_buddieson", "webpresent")  .  $buds->budCount, $budList);
    }

    // If no additional modules are registered, only show tag cloud
    if ($moduleCounter == 0)
    {
        $leftContents .= "<span style=\"text-align:center\">" . $tagCloud . "</span>";

    } else { // Else show items in multi tab box
        $leftContents .= $objTabs->show();
    }
//----------- END ADDED BY DEREK FOR EMAIL & Buddies
} else {
    $leftContents .= "<span style=\"text-align:center\">" . $tagCloud . "</span>";
}

$objDownloadCounter = $this->getObject('dbwebpresentdownloadcounter');
$downloadTable = $objDownloadCounter->getMostDownloadedTodayTable();

$objViewCounter = $this->getObject('dbwebpresentviewcounter');
$viewTable = $objViewCounter->getMostViewedTodayTable();

$statsTable = $this->newObject('htmltable', 'htmlelements');
$statsTable->startRow();
$statsTable->addCell($viewTable, '49%');
$statsTable->addCell('&nbsp;', '2%');
$statsTable->addCell($downloadTable, '49%');
$statsTable->endRow();

$leftContents .= '<br />'.$statsTable->show();

//$objLatestBlogs = $this->getObject('block_lastten'

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

        $fileLink = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
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
$table->addCell('<h3>10 Newest Uploads:</h3>'.$latestFilesContent, '37%');

$table->endRow();

echo $table->show();


$uploadbutton = new button ('upload', 'Upload');
$uploadbutton->setOnClick('document.location=\''.$this->uri(array('action'=>'upload')).'\'');

//echo $uploadbutton->show();

$uploadLink = new link ($this->uri(array('action'=>'upload')));
$uploadLink->link = 'Upload Presentation';

//echo '<p>'.$uploadLink->show().'</p>';
?>

