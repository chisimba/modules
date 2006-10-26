<?php
//class used to read all display search results for student card (SLU)
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
class searchstudcard extends object{ 

  protected $_objUser;
 
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
  /**
   *display all students that completed an information card
   */     
public  function  getAllstudents(){
            
     /**
     *create table to display studcard results
     */
     
     $results = $this->objstudcard->getallstudinfo();
     
     
    
       $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
       $this->appendArrayVar('headerParams', $css1);
       $oddEven = 'even';
       $myTable =& $this->newObject('htmltable', 'htmlelements');
       $myTable->cellspacing = '1';
       $myTable->cellpadding = '2';
       $myTable->border='0';
       $myTable->width = '100%';
       $myTable->css_class = 'highlightrows';
       $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Postal Address', null,'top','left','header');
        $myTable->addHeaderCell('Postal Code', null,'top','left','header');
        $myTable->addHeaderCell('Telephone Number', null,'top','left','header');
        $myTable->addHeaderCell('Telephone Code', null,'top','left','header');
        $myTable->endHeaderRow();
     
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       $myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['postaddress'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['postcode'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['telnumber'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['telcode'],"15%", null, "left","widelink");
       $myTable->endRow();
        
   }  
   return $myTable->show();
}
/*------------------------------------------------------------------------------*/
  /**
   *all students from a certain school
   */
public  function allstudschool($school = NULL){
        //$schoolname = $this->getParam('schoollistnames'); 
        //$results = $this->objstudcard->getstudschool($schoolname);
      if(isset($school)){
        
             $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
             $this->appendArrayVar('headerParams', $css1);
             $oddEven = 'even';
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '100%';
             $myTable->css_class = 'highlightrows';
             $myTable->row_attributes = " class = \"$oddEven\"";
          
      
            $myTable->startHeaderRow();
            $myTable->addHeaderCell('School Name', null,'top','left','header');
            $myTable->addHeaderCell('Surname', null,'top','left','header');
            $myTable->addHeaderCell('Name', null,'top','left','header');
            
            $rowcount = '0';
      
              foreach($school as $sessCard){
               
                 $oddOrEven = ($rowcount == 0) ? "odd" : "even";
                 
                 $myTable->startRow();
                 $myTable->addCell($sessCard['surname'],"15%", null, "left","widelink");
                 $myTable->addCell($sessCard['name'], "15%", null, "left","widelink");
                 $myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
                 $myTable->endRow();
             }
      }else{
             //echo 'no records found';
             $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
             $this->appendArrayVar('headerParams', $css1);
             $oddEven = 'even';
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '30%';
             $myTable->css_class = 'highlightrows';
             $myTable->row_attributes = " class = \"$oddEven\"";
             
             $myTable->startRow();
             $myTable->addCell('NO RECORDS FOUND');
             $myTable->endRow();
             
    }    
        return $myTable->show();
                 
  }      
/*------------------------------------------------------------------------------*/
  /**
   *display all students that qualify for an exemption
   */     
public  function  allwithexemption(){
      
      //exp = '0';
      //$filter = 'exemption';
           
      $results = $this->objstudcard->allstudsexemption();
      
      $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Qualify for exemption', null,'top','left','header');
        
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       $myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['exemption'],"15%", null, "left","widelink");
       $myTable->endRow();
        
   }  
   return $myTable->show();
       
  
  }	
/*------------------------------------------------------------------------------*/
  /**
   *display all students with relevant subjects
   */     
public function allwithrelsub(){
        $results = $this->objstudcard->allrelsubject();
      
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Relevant Subject', null,'top','left','header');
        
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       $myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['relevantsubject'],"15%", null, "left","widelink");
       $myTable->endRow();
        
   }  
   return $myTable->show();
  
  }
/*------------------------------------------------------------------------------*/
  /**
   *display all students by faculty
   */
  public function studfaculty(){
        
        $results = $this->objstudcard->allbyfaculty();
        
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        //$myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Faculty', null,'top','left','header');
        
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['faculty'],"15%", null, "left","widelink");
       $myTable->endRow();
        
   }  
   return $myTable->show();
  }
       
/*------------------------------------------------------------------------------*/
  /**
   *displa all students by course
   */
public   function studcourse(){
      
        $results = $this->objstudcard->allbycourse();
        
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        //$myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Course', null,'top','left','header');
        $myTable->addHeaderCell('Faculty', null,'top','left','header');
        
        
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['course'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['faculty'],"15%", null, "left","widelink");
       
       $myTable->endRow();
        
   }  
   return $myTable->show();
  }
     
/*------------------------------------------------------------------------------*/
  /**
   *display all students by area
   */
public function studarea(){
       
       $results = $this->objstudcard->allbyarea();
        
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        //$myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Postal Address', null,'top','left','header');
        $myTable->addHeaderCell('Area', null,'top','left','header');
        
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","widelink");
       $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['postaddress'],"15%", null, "left","widelink");
       //$myTable->addCell($sessCard['course'],"15%", null, "left","widelink");  -- within the activities class
       $myTable->endRow();
        
   }  
   return $myTable->show();
   }     
/*------------------------------------------------------------------------------*/
  /**
   *display all students by area
   */
public function studsdcase(){
       
       $results = $this->objstudcard->allsdcases();
        
        $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         $myTable->row_attributes = " class = \"$oddEven\"";
      
  
        $myTable->startHeaderRow();
        //$myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('SD CASE', null,'top','left','header');
        
        $rowcount = '0';
  
    foreach($results as $sessSD){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessSD['surname'], "15%", null, "left","widelink");
       $myTable->addCell($sessSD['name'],"15%", null, "left","widelink");
       $myTable->addCell($sessSD['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessSD['sdcase'],"15%", null, "left","widelink"); 
       $myTable->endRow();
        
   }  
   return $myTable->show();
   }     
/*------------------------------------------------------------------------------*/
  /**
   *display all students by area
   */
public function countstudfaculty($faculty){
       
       //$results = $this->objstudcard->allsdcases();
     if(isset($faculty)){
             $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
             $this->appendArrayVar('headerParams', $css1);
             $oddEven = 'even';
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '100%';
             $myTable->css_class = 'highlightrows';
             $myTable->row_attributes = " class = \"$oddEven\"";
          
      
            $myTable->startHeaderRow();
            $myTable->addHeaderCell('Surname', null,'top','left','header');
            $myTable->addHeaderCell('Name', null,'top','left','header');
            $myTable->addHeaderCell('Postal Address', null,'top','left','header');
            $myTable->addHeaderCell('School Name', null,'top','left','header');
            $myTable->addHeaderCell('Faculty Name', null,'top','left','header');
            $myTable->addHeaderCell('Total student count', null,'top','left','header');
            
            
            $rowcount = '0';
  
            foreach($faculty as $sessSD){
             
               $oddOrEven = ($rowcount == 0) ? "odd" : "even";
               
               $myTable->startRow();
               //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
               $myTable->addCell($sessSD['surname'], "15%", null, "left","widelink");
               $myTable->addCell($sessSD['name'],"15%", null, "left","widelink");
               $myTable->addCell($sessSD['postaddress'],"15%", null, "left","widelink");
               $myTable->addCell($sessSD['schoolname'],"15%", null, "left","widelink");
               $myTable->addCell($sessSD['faculty'],"15%", null, "left","widelink");
               $myTable->addCell($sessSD['totstud'],"15%", null, "left","widelink"); 
               $myTable->endRow();
                
           }  
           return $myTable->show();
     }
   }     
/*------------------------------------------------------------------------------*/
}//end of class  
?>
