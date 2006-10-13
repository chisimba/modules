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
	
	 //$firstHeadingLinks = array();
   /**     $firstHeadingLinks[] = array('params' => array("action" => "sumaction"), 'module' => 'modulelinkpointsto', 'linktext' => 'theTextToDisplay'); 
   *     $secondHeadingLinks = array();
   *     $secondHeadingLinks[] = array('params' => array("action" => "anotheraction"), 'module' => 'modulelinkpointsto', 'linktext' => 'theTextToDisplay'); 
   *
   *     $leftColumn = array();
   *     $leftColumn[] = array('heading' => 'First Heading', 'links' => $firstHeadingLinks);
   *     $leftColumn[] = array('heading' => 'Second Heading', 'links' => $secondHeadingLinks);
   *
   *     $this->objUtils =& $this->newObject('defaultpageutils', 'studentenquiry');
   *     echo $this->objUtils->getDefaultLayout($leftColumn);*/
  
	
   $StudentCardLink = array();
   $StudentCardLink[] = array('params' => array("action" => "studentcard"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Cards');
   $StudentCardLink[] = array('params' => array("action" => "activitylist"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'SLU Activities'); 
   $StudentCardLink[] = array('params' => array("action" => "shoollist"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'School List');
   
   $StudCardSLU = array();
   $StudCardSLU[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Card(SLU)');
   $StudCardSLU[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Card(Faculty)');
   $StudCardSLU[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'List of Activities');
   $StudCardSLU[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'List of Schools');
   
   $EntryQualify = array();      
   $EntryQualify[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Qualify for entry');
   $EntryQualify[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Total Students interested in a faculty');
   $EntryQualify[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Total of all SD Cases');
   
   $AddressGenLink = array();
   $AddressGenLink[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Address Generator');
   $AddressGenLink[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Follow Up Communication');
      
   $leftColumn = array();
   $leftColumn[] = array('heading' => 'Data Capture', 'links' => $StudentCardLink);
   $leftColumn[] = array('heading' => 'Search Facilities', 'links' => $StudCardSLU);
   $leftColumn[] = array('heading' => 'View Reports', 'links' => $EntryQualify);
   $leftColumn[] = array('heading' => 'Postings', 'links' => $AddressGenLink);
  
   
    return $leftColumn;
 }
   
   
}  
?>
