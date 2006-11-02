<?php
//class used to read all display search results for SLU activities
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
class searchactivities extends object{ 

  protected $_objUser;
 
	function init()
	{
	 try {
      //Load Classes
      $this->loadClass('dropdown', 'htmlelements');
      $this->objLanguage = &$this->newObject('language', 'language');
      $this->loadClass('htmltable','htmlelements');
      $this->objactivity = & $this->getObject('dbsluactivities','marketingrecruitmentforum');
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/
  /**
   *display all students that completed an information card
   */     
public  function  getAllactivities(){
            
     /**
     *create table to display studcard results
     */
     
     $results = $this->objactivity->getallsluactivity();
     
     
    
       $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
       $this->appendArrayVar('headerParams', $css1);
       $oddEven = 'even';
       $myTable =& $this->newObject('htmltable', 'htmlelements');
       $myTable->cellspacing = '1';
       $myTable->cellpadding = '2';
       $myTable->border='0';
       $myTable->width = '80%';
       $myTable->css_class = 'highlightrows';
       
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Date', null,'top','left','header');
        $myTable->addHeaderCell('Activity', null,'top','left','header');
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Area', null,'top','left','header');
        $myTable->addHeaderCell('Province', null,'top','left','header');
        $myTable->endHeaderRow();
     
        $rowcount = '0';
  
    foreach($results as $sessCard){
       
       
       $myTable->startRow();
       $myTable->row_attributes = " class = \"$oddEven\"";
       $myTable->addCell($sessCard['date'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['activity'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['area'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['province'],"15%", null, "left","widelink");
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       $myTable->endRow();
        
   }  
   return $myTable->show();
}
/*------------------------------------------------------------------------------*/
  /**
   *all students from a certain school
   */
public  function getactivdate($activitydate = NULL){
        
         
        //$results = $this->objactivity->getactivitydate($begindate,$enddate);
        
    if(isset($activitydate)){
       
         $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '80%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Date', null,'top','left','header');
        $myTable->addHeaderCell('Activity', null,'top','left','header');
        $myTable->endHeaderRow();
        
        
        $rowcount = '0';
  
          foreach($activitydate as $sessCard){
            
             $myTable->startRow();
             $myTable->row_attributes = " class = \"$oddEven\"";
             $myTable->addCell($sessCard['date'],"15%", null, "left","widelink");
             $myTable->addCell($sessCard['activity'], "15%", null, "left","widelink");
             $oddOrEven = ($rowcount == 0) ? "odd" : "even";
             $myTable->endRow();
         }  
      return $myTable->show();
   }    
                     
  }      
/*------------------------------------------------------------------------------*/
  /**
   *display all students that qualify for an exemption
   */     
public  function activitytype(){
      
      //exp = '0';
      //$filter = 'exemption';
           
      $results = $this->objactivity->getactivitytype();
      
      $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '30%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Activity', null,'top','left','header');
        $myTable->endHeaderRow();
       
       $rowcount = '0';
  
    foreach($results as $sessCard){
       
       $myTable->startRow();
       $myTable->row_attributes = " class = \"$oddEven\"";
       $myTable->addCell($sessCard['activity'],"15%", null, "left","widelink");
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       $myTable->endRow();
        
   }  
   return $myTable->show();
       
  
  }	
/*------------------------------------------------------------------------------*/
  /**
   *display all students with relevant subjects
   */     
public function activitybyprov(){
        $results = $this->objactivity->getactivityprovince();
      
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '80%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Activity', null,'top','left','header');
        $myTable->addHeaderCell('Province', null,'top','left','header');
        $myTable->endHeaderRow();
        
        
        $rowcount = '0';
  
    foreach($results as $sessCard){
       
       $myTable->startRow();
       $myTable->row_attributes = " class = \"$oddEven\"";
       $myTable->addCell($sessCard['activity'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['province'], "15%", null, "left","widelink");
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       $myTable->endRow();
        
   }  
   return $myTable->show();
  
  }
/*------------------------------------------------------------------------------*/
  /**
   *display all students by faculty
   */
  public function activitybyarea(){
        
        $results = $this->objactivity->getactivityarea();
        
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '80%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Activity', null,'top','left','header');
        $myTable->addHeaderCell('Area', null,'top','left','header');
        $myTable->endHeaderRow();
        
        
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
      
       
       $myTable->startRow();
       $myTable->row_attributes = " class = \"$oddEven\"";
       //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['activity'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['area'],"15%", null, "left","widelink");
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       $myTable->endRow();
        
   }  
   return $myTable->show();
  }
       
/*------------------------------------------------------------------------------*/
  /**
   *displa all students by course
   */
public   function activitybyschool($activschool = NULL){
      
        //$results = $this->objactivity->getactivityschool();
    if(isset($activschool)){
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '80%';
         
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Activity', null,'top','left','header');
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        
        
        $rowcount = '0';
  
    foreach($activschool as $sessCard){
     
       
       
       $myTable->startRow();
       $myTable->row_attributes = " class = \"$oddEven\"";
       $myTable->addCell($sessCard['activity'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['schoolname'], "15%", null, "left","widelink");
       $myTable->css_class = 'highlightrows';
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       $myTable->endRow();
        
   }  
   return $myTable->show();
  }
 }
     
/*------------------------------------------------------------------------------*/
}//end of class  
?>
