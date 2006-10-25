<?php
//template used to set the layout of all report info

   $EntryQualify = array();      
   $EntryQualify[] = array('params' => array("action" => "totalentry"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Qualify for entry');
   $EntryQualify[] = array('params' => array("action" => "totalsd"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Total of all SD Cases');
   $EntryQualify[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Total Students interested in a faculty');
   $EntryQualify[] = array('params' => array("action" => "showreportinfo"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'View Reports');
   
   $leftColumn[] = array('heading' => 'View Reports', 'links' => $EntryQualify);
   
   /**
   *create an instance of the class defaultpageutils 
   *this defines the content of the left and right columns
   *adds the quicksearch box to the rightcolumn   
   */
   
   $this->objUtils  = & $this->getObject('defaultpageutils','semsutilities');
   $displaycontent = $this->objUtils->getDefaultLayout($leftColumn);  
  
  echo  $displaycontent
    

?>
