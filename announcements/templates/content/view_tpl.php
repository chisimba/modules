<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$objDateTime = $this->getObject('dateandtime', 'utilities');

$header = new htmlHeading();
$header->type = 1;
$header->str = $announcement['title'];

echo $header->show();

echo '<p><strong>By:</strong> '.$this->objUser->fullName($announcement['createdby']).' - '.$objDateTime->formatDate($announcement['createdon']);

if ($announcement['contextid'] == 'site') {
    echo ' - <strong>Type:</strong> Site Announcement</p>';
} else {
    echo '<br /><strong>Announcement Type:</strong> Course Announcement - ';
    
    $contexts = $this->objAnnouncements->getMessageContexts($announcement['id']);
    
    if (count($contexts) > 0) {
        $divider = '';
        foreach ($contexts as $context)
        {
            echo $divider.$this->objContext->getTitle($context['contextid']);
            $divider = ', ';
        }
        
        echo '</p>';
    }
}



echo '<br />'.$announcement['message'];

$backLink = new link ($this->uri(NULL));
$backLink->link = 'Back to Announcements';

$addLink = new link ($this->uri(array('action'=>'add')));
$addLink->link = 'Post New Announcement';

echo '<p>'.$backLink->show();

if ($isAdmin || count($lecturerContext) > 0) {
    echo ' / '.$addLink->show();
}

echo '</p>';



?>