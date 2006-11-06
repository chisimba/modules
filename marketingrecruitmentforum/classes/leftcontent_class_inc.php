<?php
/**
 *create a class for the leftcolumn content of the module
 */
 
class leftcontent{

  public $objUtils;
  
  public function init()
	{
	//$this->objUtils =& $this->newObject('defaultpageutils', 'marketingrecruitmentforum');
	}
	
	
	public function leftColumnContent(){
	
	 
 //  $StudentCardLink = array();
 //  $StudentCardLink[] = array('params' => array("action" => "studentcard"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Capture Student Information');
   
   $StudentCardLink[] = array('params' => array("action" => "studentcard"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Cards');
   $StudentCardLink[] = array('params' => array("action" => "activitylist"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'SLU Activities'); 
   $StudentCardLink[] = array('params' => array("action" => "shoollist"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'School List');
//   $StudentCardLink[] = array('params' => array("action" => "showoutput"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'View Captured Data');
   
   $StudCardSLU = array();
   $StudCardSLU[] = array('params' => array("action" => "showsearchslu"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Card(SLU)');
   $StudCardSLU[] = array('params' => array("action" => "showsearchfac"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Card(Faculty)');
   $StudCardSLU[] = array('params' => array("action" => "showsearchactiities"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'List of Activities');
   $StudCardSLU[] = array('params' => array("action" => "showsearchschool"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'List of Schools');
   
   $EntryQualify = array();      
   $EntryQualify[] = array('params' => array("action" => "totalentry"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Students Qualifying for entry');
   $EntryQualify[] = array('params' => array("action" => "totalsd"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Total of all SD Cases');
   $EntryQualify[] = array('params' => array("action" => "totalfaculty"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Total Students interested in a Faculty');
   //$EntryQualify[] = array('params' => array("action" => "showreportinfo"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'View Reports');
   
   $AddressGenLink = array();
   $AddressGenLink[] = array('params' => array("action" => "showaddressgen"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Address Generator');
   $AddressGenLink[] = array('params' => array("action" => "followupletter"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Follow Up Communication');
      
   $leftColumn = array();
   $leftColumn[] = array('heading' => 'Data Capture', 'links' => $StudentCardLink);
   $leftColumn[] = array('heading' => 'Search Facilities', 'links' => $StudCardSLU);
   $leftColumn[] = array('heading' => 'View Reports', 'links' => $EntryQualify);
   $leftColumn[] = array('heading' => 'Postings', 'links' => $AddressGenLink);
  
   
    return $leftColumn;
 }
   
}  
?>
