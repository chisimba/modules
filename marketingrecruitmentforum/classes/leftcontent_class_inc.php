<?php
/**
 *create a class for the leftcolumn content of the module
 */
 
class leftcontent extends object{
  /**
   * Standarad inti function
   * @param void
   * @return void        
   */  
	function init()
	{
		$this->objLanguage =& $this->getObject('language', 'language');
	}
  /**
   * Method used to define the layout of left column links
   * @param multi- dimentional array $StudentCardLink contains all links elements all data capture interfaces
   * @param multi - dimentional array $StudCardSLU, used to create all links for all search categories
   * @param multi - dimentional array  $EntryQualify used to create all links for all reports categories
   * @param multi - dimentional array  $AddressGenLink used to create links for communication category
   * @param multi - dimentional array  $leftColumn used to create headings for all array link info
   * @return array  $leftColumn            
   */   
  
	public function leftColumnContent(){
	 $studentcard =        $this->objLanguage->languageText('mod_marketingrecruitmentforum_studcard','marketingrecruitmentforum');
	 $sluactivity =        $this->objLanguage->languageText('mod_marketingrecruitmentforum_sluactivity22','marketingrecruitmentforum');
	 $schoollist =         $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoollist','marketingrecruitmentforum');
	 
   $studentcardslu  =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_studcardslu1','marketingrecruitmentforum');
   $studcardfaculty =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_studcardfaculty1','marketingrecruitmentforum');
   $studcardfaculty2 =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_studcardfaculty2','marketingrecruitmentforum');
   $listacitities   =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_listacitities','marketingrecruitmentforum');
   $listofschools   =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_listofschools','marketingrecruitmentforum');
   
   $entryQualify    =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_entryqualify101','marketingrecruitmentforum');
   $totsdcase       =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_stotsdcase','marketingrecruitmentforum');
   $totstudinterest =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_reportfaculty11','marketingrecruitmentforum');
   $staffdata       =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_reportstaffinfo','marketingrecruitmentforum');
   
   $addgenerator    =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_addgenerator','marketingrecruitmentforum');
   $communication   =    $this->objLanguage->languageText('mod_marketingrecruitmentforum_communication','marketingrecruitmentforum');
   

   $StudentCardLink[] = array('params' => array("action" => "studentcard"), 'module' => 'marketingrecruitmentforum', 'linktext' => $studentcard);
   $StudentCardLink[] = array('params' => array("action" => "activitylist"), 'module' => 'marketingrecruitmentforum', 'linktext' => $sluactivity); 
   $StudentCardLink[] = array('params' => array("action" => "shoollist"), 'module' => 'marketingrecruitmentforum', 'linktext' => $schoollist);
   $StudentCardLink[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => '');

   
   $StudCardSLU = array();
   $StudCardSLU[] = array('params' => array("action" => "showsearchslu"), 'module' => 'marketingrecruitmentforum', 'linktext' => $studentcardslu);
   $StudCardSLU[] = array('params' => array("action" => "showsearchfac"), 'module' => 'marketingrecruitmentforum', 'linktext' => $studcardfaculty);
   $StudCardSLU[] = array('params' => array("action" => "showsearchfac2"), 'module' => 'marketingrecruitmentforum', 'linktext' => $studcardfaculty2);
   $StudCardSLU[] = array('params' => array("action" => "showsearchactiities"), 'module' => 'marketingrecruitmentforum', 'linktext' => $listacitities);
   $StudCardSLU[] = array('params' => array("action" => "showsearchschool"), 'module' => 'marketingrecruitmentforum', 'linktext' => $listofschools);
   $StudCardSLU[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => '');
   
   $EntryQualify = array(); 
   $EntryQualify[] = array('params' => array("action" => "totalentry"), 'module' => 'marketingrecruitmentforum', 'linktext' => $entryQualify);
   $EntryQualify[] = array('params' => array("action" => "totalsd"), 'module' => 'marketingrecruitmentforum', 'linktext' => $totsdcase);
   $EntryQualify[] = array('params' => array("action" => "totalfaculty"), 'module' => 'marketingrecruitmentforum', 'linktext' => $totstudinterest);
   $EntryQualify[] = array('params' => array("action" => "facultyuse"), 'module' => 'marketingrecruitmentforum', 'linktext' => $staffdata);
   $EntryQualify[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => '');
   
   $AddressGenLink = array();
   $AddressGenLink[] = array('params' => array("action" => "showaddressgen"), 'module' => 'marketingrecruitmentforum', 'linktext' => $addgenerator);
   //$AddressGenLink[] = array('params' => array("action" => "followupletter"), 'module' => 'marketingrecruitmentforum', 'linktext' => $communication);
   //$AddressGenLink[] = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => '');
      
   $leftColumn = array();
   $leftColumn[] = array('heading' => 'Data Capture', 'links' => $StudentCardLink);
   $leftColumn[] = array('heading' => 'Search Facilities', 'links' => $StudCardSLU);
   $leftColumn[] = array('heading' => 'View Reports', 'links' => $EntryQualify);
   $leftColumn[] = array('heading' => 'Communication', 'links' => $AddressGenLink);
  
   
    return $leftColumn;
 }
   
}  
?>
