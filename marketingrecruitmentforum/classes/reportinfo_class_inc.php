<?php
//class used to display all report information
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object generates data used to display search results for student card (SLU) 
* @package 
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class reportinfo extends object{ 

  //protected $_objUser;
 
	function init()
	{
	 try {
      //Load Classes
      $this->loadClass('dropdown', 'htmlelements');
      $this->objLanguage = &$this->newObject('language', 'language');
      $this->loadClass('htmltable','htmlelements');
      $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/
function displaysdcases(){
  
      /**
     *create table to display all sd cases results
     */
     
     $results = $this->objstudcard->sdcases();
     
     
    
         $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '80%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
          $myTable->startHeaderRow();
          $myTable->addHeaderCell('Surname', null,'top','left','header');
          $myTable->addHeaderCell('Name', null,'top','left','header');
          $myTable->addHeaderCell('SD Case', null,'top','left','header');
          $myTable->endHeaderRow();
     
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
         $myTable->startRow();
         $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
         $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
         $myTable->addCell('Yes', "15%", null, "left","widelink");
//         $myTable->addCell($sessCard['sdcase'], "15%", null, "left","widelink");
         $myTable->endRow();
        
   }  
   return $myTable->show();
}
/*------------------------------------------------------------------------------*/  
function entryQualification(){
        
         /**
     *create table to display all sd cases results
     */
     
     $results = $this->objstudcard->getstudqualify();
     
     
    
         $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '80%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
         $myTable->startHeaderRow();
         $myTable->addHeaderCell('Name', null,'top','left','header');
         $myTable->addHeaderCell('Surname', null,'top','left','header');
         $myTable->addHeaderCell('Exemption', null,'top','left','header');
         $myTable->addHeaderCell('Relevant Subject', null,'top','left','header');
         $myTable->endHeaderRow();
     
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
         $myTable->startRow();
         $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
         $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
         $myTable->addCell('Yes', "15%", null, "left","widelink");
         $myTable->addCell('Yes', "15%", null, "left","widelink");
         $myTable->endRow();
        
   }  
   return $myTable->show(); 
}
/*------------------------------------------------------------------------------*/  
function facultyinterest(){
        
         /**
     *create table to display all sd cases results
     */
     
        $results = $this->objstudcard->getallstudinfo();
     
     
    
         $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '80%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
         $myTable->startHeaderRow();
         $myTable->addHeaderCell('Name', null,'top','left','header');
         $myTable->addHeaderCell('Surname', null,'top','left','header');
         $myTable->addHeaderCell('faculty', null,'top','left','header');
         $myTable->endHeaderRow();
     
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
         $myTable->startRow();
         $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
         $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
         $myTable->addCell($sessCard['faculty'], "15%", null, "left","widelink");
         $myTable->endRow();
        
   }  
   return $myTable->show(); 
}
/*------------------------------------------------------------------------------*/

}//end of class


?>
