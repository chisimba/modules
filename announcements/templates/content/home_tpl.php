<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('add');

$addLink = new link ($this->uri(array('action'=>'add')));
$addLink->link = $objIcon->show();

$allAnn = "";
$courseAnn ="";

if ($this->objContext->getContextCode() != '') {
    $numContextAnnouncements = $this->objAnnouncements->getNumContextAnnouncements($this->objContext->getContextCode());
    
    $header = new htmlHeading();
    $header->type = 1;
    $header->str = ucwords($this->objLanguage->code2Txt('mod_announcements_contextannouncements',
      'announcements', NULL, '[-context-] Announcements'))
      . ' - ' . $this->objContext->getTitle($this->objContext->getContextCode())
      . ' ('.$numContextAnnouncements.')';
    
    if ($isAdmin || count($lecturerContext) > 0) {
        $header->str .= ' '.$addLink->show();
    }
    
    $courseAnn .=  $header->show();
    
    $objPagination = $this->newObject('pagination', 'navigation');
    $objPagination->module = 'announcements';
    $objPagination->action = 'getcontextajax';
    $objPagination->id = 'pagenavigation_context';
    
    $itemsPerPage = ($numContextAnnouncements - ($numContextAnnouncements % $this->itemsPerPage)) / $this->itemsPerPage;
    if ($numContextAnnouncements % $this->itemsPerPage != 0) {
        $itemsPerPage++;
    }
    
    $objPagination->numPageLinks = $itemsPerPage;
    
    $courseAnn .= $objPagination->show();
    $courseAnn = "\n<div class='outerwrapper'>$courseAnn</div>\n";
    // Course announcements rendered here.
    echo $courseAnn;
}

// All announcements below.
$header = new htmlHeading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_announcements_myannouncements', 'announcements', 'All My Announcements').' ('.$numAnnouncements.')';

if ($isAdmin || count($lecturerContext) > 0) {
    $header->str .= ' '.$addLink->show();
}

$allAnn .= $header->show();

$objPagination = $this->newObject('pagination', 'navigation');
$objPagination->module = 'announcements';
$objPagination->action = 'getajax';
$objPagination->id = 'pagenavigation_all';

$itemsPerPage = ($numAnnouncements - ($numAnnouncements % $this->itemsPerPage)) / $this->itemsPerPage;
if ($numAnnouncements % $this->itemsPerPage != 0) {
    $itemsPerPage++;
}
$objPagination->numPageLinks = $itemsPerPage;
$allAnn .= $objPagination->show();
$allAnn  = "\n<div class='outerwrapper'>$allAnn </div>\n";
$addLink = new link ($this->uri(array('action'=>'add')));
$addLink->link = $this->objLanguage->languageText('mod_announcements_postnewannouncement', 'announcements', 'Post New Announcement');
echo $allAnn;

// Add new announcement link
if ($isAdmin || count($lecturerContext) > 0) {
    echo "<div class='adminadd'></div><div class='adminaddlink'>" . $addLink->show() . "</div>";
}
?>