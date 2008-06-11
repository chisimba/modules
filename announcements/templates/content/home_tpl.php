<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('add');

$addLink = new link ($this->uri(array('action'=>'add')));
$addLink->link = $objIcon->show();

if ($this->objContext->getContextCode() != '') {
    $numContextAnnouncements = $this->objAnnouncements->getNumContextAnnouncements($this->objContext->getContextCode());
    
    $header = new htmlHeading();
    $header->type = 1;
    $header->str = 'Course Announcements - '.$this->objContext->getTitle($this->objContext->getContextCode()).' ('.$numContextAnnouncements.')';
    
    if ($isAdmin || count($lecturerContext) > 0) {
        $header->str .= ' '.$addLink->show();
    }
    
    echo $header->show();
    
    $objPagination = $this->newObject('pagination', 'navigation');
    $objPagination->module = 'announcements';
    $objPagination->action = 'getcontextajax';
    $objPagination->id = 'pagenavigation_context';
    
    $itemsPerPage = ($numContextAnnouncements - ($numContextAnnouncements % $this->itemsPerPage)) / $this->itemsPerPage;
    if ($numContextAnnouncements % $this->itemsPerPage != 0) {
        $itemsPerPage++;
    }
    
    $objPagination->numPageLinks = $itemsPerPage;
    
    echo $objPagination->show();
    
    
    echo '<br />';
}

$header = new htmlHeading();
$header->type = 1;
$header->str = 'All My Announcements ('.$numAnnouncements.')';

if ($isAdmin || count($lecturerContext) > 0) {
    $header->str .= ' '.$addLink->show();
}

echo $header->show();

$objPagination = $this->newObject('pagination', 'navigation');
$objPagination->module = 'announcements';
$objPagination->action = 'getajax';
$objPagination->id = 'pagenavigation_all';

$itemsPerPage = ($numAnnouncements - ($numAnnouncements % $this->itemsPerPage)) / $this->itemsPerPage;
if ($numAnnouncements % $this->itemsPerPage != 0) {
    $itemsPerPage++;
}

$objPagination->numPageLinks = $itemsPerPage;

echo $objPagination->show();

$addLink = new link ($this->uri(array('action'=>'add')));
$addLink->link = 'Post New Announcement';

echo $addLink->show();

?>