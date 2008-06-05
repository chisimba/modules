<?php
/*
$this->setLayoutTemplate('announcements_layout_tpl.php');
// Show the heading.
$objHeading =& $this->getObject('htmlheading','htmlelements');
$objHeading->type=4;
$objHeading->str="&nbsp;";


//Use to check for admin user:
$isAdmin = $this->objUser->isAdmin();
$isLecturer = $this->objUser->isLecturer();	
$isInContext=$this->objContext->isInContext();


if ($isInContext) {
	if(($isLecturer or $isAdmin)){
		$objAddIcon = $this->newObject('geticon', 'htmlelements');
		$objLink = $this->uri(array(
	    'action' => 'link'
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
	    'action' => 'link'
		));
		$objAddIcon->setIcon("add", "gif");
		$objAddIcon->alt = $objLanguage->languageText('mod_announcements_addicon', 'announcements');
		$add = $objAddIcon->getAddIcon($objLink);
		$add = $objAddIcon->getAddIcon($objLink);
	} else {
		$add = '';
	}
}
// Create add icon and link to add template


// Create header with add icon
$pgTitle = &$this->getObject('htmlheading', 'htmlelements');
$pgTitle->type = 4;

$latest = $objLanguage->languageText('mod_announcements_last', 'announcements');
$latestAnnounce = $objLanguage->languageText('mod_announcements_head', 'announcements');
$pgTitle->str = $latest."&nbsp;".ucwords($contextTitle)."&nbsp;".$latestAnnounce."&nbsp;". $add;
//create array to hold data and set the language items
$tableRow = array();


// Create the table header for display
$objTableClass = $this->newObject('htmltable', 'htmlelements');
//$objTableClass->addHeader($tableHd, "heading");
$index = 0;
$rowcount = 0;

//language item for no records
$norecords = $objLanguage->languageText('mod_announcements_nodata', 'announcements');
//A statement not to display the records if it is empty.
if (empty($record)) {
    $objTableClass->addCell($norecords, NULL, NULL, 'left', 'noRecordsMessage', 'colspan="4"');

}else{
        // Set odd even colour scheme
        $class = ($rowcount%2 == 0) ? 'odd' : 'even';
        $objTableClass->startRow();
       //add title
        $title = $record['title'] ;
        //$records == $objUser->userId();
	$objTableClass->addCell('', '', 'left', 'left', $class,'colspan=3');
	$objTableClass->endRow();

 	$objTableClass->startRow();
        //add message
        $message = $record['message'];
        $objTableClass->addCell($message, '', 'left', 'left', $class,'colspan=3');

	$objTableClass->endRow();

 	
	
	$objTableClass->startRow();

	//get author details
	//add author id
        $createdbyid = $record['createdby'];
	//get author full names
	$createdby=$this->objUser->fullname($createdbyid);
        
	
	//create a link to users profile	
	$this->loadClass('link', 'htmlelements');
        //$objIcon = $this->newObject('geticon', 'htmlelements');
        $link = new link($this->uri(array(
            'action' => '',
            'id' => $createdbyid
        ) , 'security'));
        //$objIcon->setIcon('edit');
        $link->link = $createdby;
        $createdby = $link->show();
	
	//add date created
        $createdon = $record['createdon'];
	//format date
	$createdon=$this->objDate->formatDate($createdon);
	
        $objTableClass->addCell('<b>Created By:</b> '.$createdby.'&nbsp; &nbsp; <b>On:</b> '.$createdon, '', '', 'left', $class);
 	//add author
        
	//get id
	$id=$record['id'];
	//check if user is admin or a lecture, if either is true show the delete icon
	if($isAdmin or $isLecturer){
        // Create delete icon and delete action
        $objDelIcon = $this->newObject('geticon', 'htmlelements');
        $delLink = array(
            'action' => 'delete',
            'id' => $id,
            'module' => 'announcements',
            'confirm' => 'yes',
        );
	
        $deletephrase = $objLanguage->languageText('mod_announcements_deleteicon', 'announcements');
        $conf = $objDelIcon->getDeleteIconWithConfirm('', $delLink, 'announcements', $deletephrase);
        $update = $conf;
	}
        //$records == $objUser->userId();
	
        $objTableClass->addCell($update, '', 'right', 'right', $class);
        //check if user is admin or a lecture, if either is true show the edit icon
	// Create edit icon and action
	if($isAdmin or $isLecturer){
        $this->loadClass('link', 'htmlelements');
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $link = new link($this->uri(array(
            'action' => 'edit',
            'id' => $id
        ) , 'announcements'));
        $objIcon->setIcon('edit');
        $link->link = $objIcon->show();
        $update = $link->show();
	}
        $objTableClass->addCell($update, '', 'right', 'right', $class);
        $objTableClass->endRow();
	
	//add cell to make the table look neat
	$objTableClass->startRow();
        
        $objTableClass->addCell('&nbsp; &nbsp;');

	$objTableClass->endRow();


}
//shows the array in a table
echo "<legend border='style:border 1px solid #cccccc'>";
$ret = $objTableClass->show();
//$ret="<div class='wrapperLightBkg' border='style:border 1px solid #cccccc'>".$ret."</div>";
echo "</legend>";

if (empty($record)) {
	echo $pgTitle->show()."<br>".$this->objFeatureBox->show(null, $ret);	
} else {
	echo $pgTitle->show()."<br>".$this->objFeatureBox->show("$title", $ret);
}
*/

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
    echo $pgTitle->show();
}
echo "<br>";	
echo $objTableSiteClass->show();   
?>