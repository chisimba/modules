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
class searchschools extends object{ 

  protected $_objUser;
 
	function init()
	{
	 try {
      //Load Classes
      $this->loadClass('dropdown', 'htmlelements');
      $this->objLanguage = &$this->newObject('language', 'language');
      $this->loadClass('htmltable','htmlelements');
      $this->objschool = & $this->newObject('dbschoollist','marketingrecruitmentforum');
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/
  /**
   *display all students that completed an information card
   */     
public  function  getAllschools(){
            
     /**
     *create table to display studcard results
     */
     
     $results = $this->objschool->getallsschools();
     
     
    
       $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
       $this->appendArrayVar('headerParams', $css1);
       $oddEven = 'even';
       $myTable =& $this->newObject('htmltable', 'htmlelements');
       $myTable->cellspacing = '1';
       $myTable->cellpadding = '2';
       $myTable->border='0';
       $myTable->width = '20%';
       $myTable->css_class = 'highlightrows';
       $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->endHeaderRow();
     
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       $myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->endRow();
        
   }  
   return $myTable->show();
}
/*------------------------------------------------------------------------------*/
  /**
   *display certain school
   */
public  function schoolbyname($schoolbyname = NULL){
        
         
        //$results = $this->objschool->getschoolbyname();
         if(isset($schoolbyname)){
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
                $myTable->addHeaderCell('School Name', null,'top','left','header');
                $myTable->addHeaderCell('Address', null,'top','left','header');
                $myTable->addHeaderCell('Telephone Number', null,'top','left','header');
                $myTable->addHeaderCell('Fax Number', null,'top','left','header');
                $myTable->addHeaderCell('Email Address', null,'top','left','header');
                $myTable->addHeaderCell('Principal', null,'top','left','header');
                $myTable->addHeaderCell('Guidance Teacher', null,'top','left','header');
                $myTable->endHeaderRow();
                
                
                $rowcount = '0';
          
            foreach($schoolbyname as $sessCard){
             
               $oddOrEven = ($rowcount == 0) ? "odd" : "even";
               
               $myTable->startRow();
               $myTable->addCell($sessCard['schoolname'],"20%", null, "left","widelink");
               $myTable->addCell($sessCard['schooladdress'], "20%", null, "left","widelink");
               $myTable->addCell($sessCard['telnumber'], "20%", null, "left","widelink");
               $myTable->addCell($sessCard['faxnumber'], "20%", null, "left","widelink");      
               $myTable->addCell($sessCard['email'], "20%", null, "left","widelink");      
               $myTable->addCell($sessCard['principal'], "20%", null, "left","widelink");      
               $myTable->addCell($sessCard['guidanceteacher'], "20%", null, "left","widelink");            
               $myTable->endRow();
                
           }  
            return $myTable->show();
          }
                 
  }      
/*------------------------------------------------------------------------------*/
  /**
   *display all province schools by area
   */     
public  function schoolbyarea(){
      
      //exp = '0';
      //$filter = 'exemption';
           
      $results = $this->objschool->getschoolbyarea();
      
      $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '60%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Area', null,'top','left','header');
        $myTable->endHeaderRow();
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       $myTable->addCell($sessCard['schoolname'],"25%", null, "left","widelink");
       $myTable->addCell($sessCard['area'],"25%", null, "left","widelink");  //link with slu activity 
       $myTable->endRow();
        
   }  
   return $myTable->show();
       
  
  }	
/*------------------------------------------------------------------------------*/
  /**
   *display all schools by province
   */     
public function activitybyprov(){
        $results = $this->objschool->getschoolbyprovince();
      
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '60%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
            
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Province', null,'top','left','header');
        $myTable->endHeaderRow();
        
        
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       $myTable->addCell($sessCard['schoolname'],"25%", null, "left","widelink");
       $myTable->addCell($sessCard['prov'], "25%", null, "left","widelink");  //-- slu table
       $myTable->endRow();
        
   }  
   return $myTable->show();
  
  }
/*------------------------------------------------------------------------------*/
}//end of class  
?>
