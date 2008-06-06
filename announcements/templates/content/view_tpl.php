<?php
$this->setLayoutTemplate('announcements_layout_tpl.php');
$this->loadClass('link', 'htmlelements');

$contextid = $contextCode;

// Show the heading.
$objHeading =& $this->getObject('htmlheading','htmlelements');
$objHeading->type=4;
$objHeading->str="&nbsp;";


//Use to check for admin user:
$isAdmin = $this->objUser->isAdmin();
$isLecturer = $this->objUser->isLecturer();	
$isInContext=$this->objContext->isInContext();

// Create the table header for display
$objTableClass = $this->newObject('htmltable', 'htmlelements');
//$objTableClass->addHeader($tableHd, "heading");
$index = 0;
$rowcount = 0;

//language item for no records
$norecords = $objLanguage->languageText('mod_announcements_nodata', 'announcements');

if($contextid != "root"){
	if ($isInContext) {
		if(($isLecturer or $isAdmin)){
			$objAddIcon = $this->newObject('geticon', 'htmlelements');
			$objLink = $this->uri(array(
		    'action' => 'link', 'contextAnnounce'=>$contextid
			));
			$objAddIcon->setIcon("add", "gif");
			$objAddIcon->alt = $objLanguage->languageText('mod_announcements_addicon', 'announcements');
			$add = $objAddIcon->getAddIcon($objLink);
		} else {
			$add = '';
		}
	} else {
		if(($isAdmin)){
			$objAddIcon = $this->newObject('geticon', 'htmlelements');
			$objLink = $this->uri(array(
		    'action' => 'link', 'contextAnnounce'=>$contextid
			));
			$objAddIcon->setIcon("add", "gif");
			$objAddIcon->alt = $objLanguage->languageText('mod_announcements_addicon', 'announcements');
			$add = $objAddIcon->getAddIcon($objLink);
		} else {
			$add = '';
		}
	}
	
	// Create header with add icon
	$pgTitle = &$this->getObject('htmlheading', 'htmlelements');
	$pgTitle->type = 4;
	
	$courseAnnounce = $objLanguage->languageText('mod_announcements_course', 'announcements');
	$pgTitle->str = $courseAnnounce."&nbsp;".ucwords($contextTitle)."&nbsp;". $add;
	echo $pgTitle->show();
	//A statement not to display the records if it is empty.
	if (empty($allCourse)) {
	    $objTableClass->addCell($norecords, NULL, NULL, 'left', 'noRecordsMessage', 'colspan="4"');
	
	}else {
		//Create an array for each value in the table.
	    foreach($allCourse as $course) {
			$rowcount++;
	        // Set odd even colour scheme
	        $class = ($rowcount%2 == 0) ? 'odd' : 'even';
	        $objTableClass->startRow();
	        //add title
	        $title = $course['title'] ;
	        $titleLink = new link($this->uri(array('action' => 'viewannouncement', 'id'=>$course['id'], 'contextAnnounce'=>$course['contextid'], 'contextid'=>$contextid)));
	        $titleLink->link = $title;
	        //$records == $objUser->userId();
			$objTableClass->addCell('<b>'.$rowcount.': '.$titleLink->show().'</b>', '', 'left', 'left', $class,'colspan=3');
			$objTableClass->endRow();    	
	    }
	    echo "<br>";
	}		
	echo $objTableClass->show();
	echo "<br>";
}

$objTableSiteClass = $this->newObject('htmltable', 'htmlelements');

// Create header with add icon
$pgTitle = &$this->getObject('htmlheading', 'htmlelements');
$pgTitle->type = 4;

if ($isInContext) {
	if(($isLecturer or $isAdmin)){
		$objAddIcon = $this->newObject('geticon', 'htmlelements');
		$objLink = $this->uri(array(
	    'action' => 'link', 'contextAnnounce'=>'root'
		));
		$objAddIcon->setIcon("add", "gif");
		$objAddIcon->alt = $objLanguage->languageText('mod_announcements_addicon', 'announcements');
		$add = $objAddIcon->getAddIcon($objLink);
	} else {
		$add = '';
	}
} else {
	if(($isAdmin)){
		$objAddIcon = $this->newObject('geticon', 'htmlelements');
		$objLink = $this->uri(array(
	    'action' => 'link', 'contextAnnounce'=>'root'
		));
		$objAddIcon->setIcon("add", "gif");
		$objAddIcon->alt = $objLanguage->languageText('mod_announcements_addicon', 'announcements');
		$add = $objAddIcon->getAddIcon($objLink);
	} else {
		$add = '';
	}
}

$contextTitle = $objLanguage->languageText('mod_announcements_siteword', 'announcements');
$courseAnnounce = $objLanguage->languageText('mod_announcements_site', 'announcements');
$pgTitle->str = $courseAnnounce."&nbsp;".$contextTitle."&nbsp;". $add;
echo $pgTitle->show();
$index = 0;
$rowcount = 0;

if (empty($allSite)) {
    $objTableSiteClass->addCell($norecords, NULL, NULL, 'left', 'noRecordsMessage', 'colspan="4"');
}else {
	//Create an array for each value in the table.
    foreach($allSite as $course) {
		$rowcount++;
        // Set odd even colour scheme
        $class = ($rowcount%2 == 0) ? 'odd' : 'even';
        $objTableSiteClass->startRow();
        
        //add title
        $title = $course['title'] ;
        $titleLink = new link($this->uri(array('action' => 'viewannouncement', 'id'=>$course['id'], 'contextAnnounce'=>$course['contextid'], 'contextid'=>$contextid)));
        $titleLink->link = $title;
        
        //$records == $objUser->userId();
		$objTableSiteClass->addCell('<b>'.$rowcount.'. '.$titleLink->show().'</b>', '', 'left', 'left', $class,'colspan=3');
		$objTableSiteClass->endRow();    	
    }
    
}
echo "<br>";	
echo $objTableSiteClass->show();   
?>