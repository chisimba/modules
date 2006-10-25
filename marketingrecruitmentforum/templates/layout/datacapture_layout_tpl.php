<?php
//create a template for the layout design of data capture info
 
   
   $StudentCardLink[] = array('params' => array("action" => "studentcard"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Cards');
   $StudentCardLink[] = array('params' => array("action" => "activitylist"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'SLU Activities'); 
   $StudentCardLink[] = array('params' => array("action" => "shoollist"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'School List');
   $StudentCardLink[] = array('params' => array("action" => "showoutput"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'View Captured Data');
   
   $leftColumn = array();
   $leftColumn[] = array('heading' => 'Data Capture', 'links' => $StudentCardLink);
   
   
  /**
   *create an instance of the class defaultpageutils 
   *this defines the content of the left and right columns
   *adds the quicksearch box to the rightcolumn   
   */
   
   $this->objUtils  = & $this->getObject('defaultpageutils','semsutilities');
   $displaycontent = $this->objUtils->getDefaultLayout($leftColumn);  
  
  echo  $displaycontent
?>
