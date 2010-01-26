<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$objDateTime = $this->getObject('dateandtime', 'utilities');

$header = new htmlHeading();
$header->type = 1;
$header->str = $announcement['title'];



// Check if User has permission
if ($this->checkPermission($announcement['id'])) {

    $objIcon = $this->newObject('geticon', 'htmlelements');
    $objIcon->setIcon('edit');
    
    $editLink = new link ($this->uri(array('action'=>'edit', 'id'=>$announcement['id'])));
    $editLink->link = $objIcon->show();
    //Removed by Wesley Nitscke .. cannot edit an announcement once it has been sent out.. post a new one rather
   // $header->str .= ' '.$editLink->show();
    
    $deleteArray = array('action'=>'delete', 'id'=>$announcement['id']);
    
    $deleteLink = $objIcon->getDeleteIconWithConfirm($announcement['id'], $deleteArray, 'announcements');
    
    
    $header->str .= ' '.$deleteLink;
}


echo $header->show();

echo '<p><strong>By:</strong> '.$this->objUser->fullName($announcement['createdby']).' - '.$objDateTime->formatDate($announcement['createdon']);

if ($announcement['contextid'] == 'site') {
    echo ' - <strong>'.$this->objLanguage->languageText('word_type', 'system', 'Type').':</strong> '.$this->objLanguage->languageText('mod_announcements_siteannouncement', 'announcements', 'Site Announcement').'</p>';
} else {
    echo '<br /><strong>'.$this->objLanguage->languageText('mod_announcements_announcementtype', 'announcements', 'Announcement Type').':</strong> '.ucwords($this->objLanguage->code2Txt('mod_announcements_contextannouncement', 'announcements', NULL, '[-context-] Announcement')).' - ';
    
    $contexts = $this->objAnnouncements->getMessageContexts($announcement['id']);
    
    if (count($contexts) > 0) {
        $divider = '';
        foreach ($contexts as $context)
        {
            echo $divider.$this->objContext->getTitle($context);
            $divider = ', ';
        }
        
        echo '</p>';
    }
}



echo '<br />'.$announcement['message'];

$backLink = new link ($this->uri(NULL));
$backLink->link = $this->objLanguage->languageText('mod_announcements_back', 'announcements', 'Back to Announcements');

$addLink = new link ($this->uri(array('action'=>'add')));
$addLink->link = $this->objLanguage->languageText('mod_announcements_postnewannouncement', 'announcements', 'Post New Announcement');

echo '<p>'.$backLink->show();
if ($isAdmin || count($lecturerContext) > 0) {
    echo ' / '.$addLink->show();
}
echo '</p>';



?>
