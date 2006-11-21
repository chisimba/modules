<?php
//class to display all faculty related information
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object generates data used to display search results for student card (faculty) 
* @package 
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class searchfaculty extends object{ 

//  protected $_objUser;
 
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
   *function to display all matriculants that completed info cards for specific faculty
   */
   public  function studentsbyfaculty($facultyval = NULL){
        
         
        //$results = $this->objschool->getschoolbyname();
         if(isset($facultyval)){
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
                $myTable->addHeaderCell('Surname', null,'top','left','header');
                $myTable->addHeaderCell('Name', null,'top','left','header');
                $myTable->addHeaderCell('Faculty', null,'top','left','header');
                $myTable->endHeaderRow();
                
                
                $rowcount = '0';
          
            foreach($facultyval as $sessFac){
             
               
               
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               $myTable->addCell($sessFac['surname'],"20%", null, "left","widelink");
               $myTable->addCell($sessFac['name'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['faculty'], "20%", null, "left","widelink");
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;
               $myTable->endRow();
                
           }  
            return $myTable->show();
          }
                 
  }      
/*------------------------------------------------------------------------------*/     
  /**
   *function to display all matriculants that have an exemption and entered for specific faculty
   */     
    public  function exemptionbyfaculty($facultyexmp = NULL){
        
         
        //$results = $this->objschool->getschoolbyname();
         if(isset($facultyexmp)){
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
                $myTable->addHeaderCell('Surname', null,'top','left','header');
                $myTable->addHeaderCell('Name', null,'top','left','header');
                $myTable->addHeaderCell('School Name', null,'top','left','header');
                $myTable->addHeaderCell('Exemption', null,'top','left','header');
                $myTable->addHeaderCell('Faculty', null,'top','left','header');
                $myTable->endHeaderRow();
                
                
                $rowcount = '0';
          
            foreach($facultyexmp as $sessFac){
             
               
               
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               $myTable->addCell($sessFac['surname'],"20%", null, "left","widelink");
               $myTable->addCell($sessFac['name'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['schoolname'], "20%", null, "left","widelink");
               $myTable->addCell('Yes', "20%", null, "left","widelink");
//               $myTable->addCell($sessFac['exemption'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['faculty'], "20%", null, "left","widelink");
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;
               $myTable->endRow();
                
           }  
            return $myTable->show();
          }
                 
  }      
/*------------------------------------------------------------------------------*/    

/**
   *function to display all matriculants that have an exemption and entered for specific faculty
   */     
    public  function relsubjbyfaculty($facsubj = NULL){
        
         
        //$results = $this->objschool->getschoolbyname();
         if(isset($facsubj)){
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
                $myTable->addHeaderCell('Surname', null,'top','left','header');
                $myTable->addHeaderCell('Name', null,'top','left','header');
                $myTable->addHeaderCell('School Name', null,'top','left','header');
                $myTable->addHeaderCell('Relevant Subject', null,'top','left','header');
                $myTable->addHeaderCell('Faculty', null,'top','left','header');
                $myTable->endHeaderRow();
                
                
                $rowcount = '0';
          
            foreach($facsubj as $sessFac){
             
               
               
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               $myTable->addCell($sessFac['surname'],"20%", null, "left","widelink");
               $myTable->addCell($sessFac['name'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['schoolname'], "20%", null, "left","widelink");
               $myTable->addCell('Yes', "20%", null, "left","widelink");
               //$myTable->addCell($sessFac['relevantsubject'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['faculty'], "20%", null, "left","widelink");
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;
               $myTable->endRow();
                
           }  
            return $myTable->show();
          }
                 
  }      
/*------------------------------------------------------------------------------*/

/**
   *function to display all matriculants that have an exemption and entered for specific faculty
   */     
    public  function coursebyfaculty($faccourse = NULL){
        
         
        //$results = $this->objschool->getschoolbyname();
         if(isset($faccourse)){
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
                $myTable->addHeaderCell('Surname', null,'top','left','header');
                $myTable->addHeaderCell('Name', null,'top','left','header');
                $myTable->addHeaderCell('School Name', null,'top','left','header');
                $myTable->addHeaderCell('Course', null,'top','left','header');
                $myTable->addHeaderCell('Faculty', null,'top','left','header');
                $myTable->endHeaderRow();
                
                
                $rowcount = '0';
          
            foreach($faccourse as $sessFac){
             
               
               
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               $myTable->addCell($sessFac['surname'],"20%", null, "left","widelink");
               $myTable->addCell($sessFac['name'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['schoolname'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['course'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['faculty'], "20%", null, "left","widelink");
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;
               $myTable->endRow();
                
           }  
            return $myTable->show();
          }
                 
  }   
/*------------------------------------------------------------------------------*/

/**
   *function to display all matriculants that have an exemption and entered for specific faculty
   */     
    public  function sdcasebyfaculty($facsdcase = NULL){
        
         
        //$results = $this->objschool->getschoolbyname();
         if(isset($facsdcase)){
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
                $myTable->addHeaderCell('Surname', null,'top','left','header');
                $myTable->addHeaderCell('Name', null,'top','left','header');
                $myTable->addHeaderCell('School Name', null,'top','left','header');
                $myTable->addHeaderCell('SD CASE', null,'top','left','header');
                $myTable->addHeaderCell('Faculty', null,'top','left','header');
                $myTable->endHeaderRow();
                
                
                $rowcount = '0';
          
            foreach($facsdcase as $sessFac){
             
               
               
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               $myTable->addCell($sessFac['surname'],"20%", null, "left","widelink");
               $myTable->addCell($sessFac['name'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['schoolname'], "20%", null, "left","widelink");
               $myTable->addCell('Yes', "20%", null, "left","widelink");
               //$myTable->addCell($sessFac['sdcase'], "20%", null, "left","widelink");
               $myTable->addCell($sessFac['faculty'], "20%", null, "left","widelink");
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;
               $myTable->endRow();
                
           }  
            return $myTable->show();
          }
                 
  }   
/*------------------------------------------------------------------------------*/  
  
}//end class
?>
