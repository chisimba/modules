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
public function schoolbyname($schoolbyname){
        
         $this->loadClass('link', 'htmlelements');      
         $SchoolLink = new link($this->uri(array('action' => 'captureschool', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Capture school details')));
         $SchoolLink->link = 'Capture school details';
        
         if(!empty($schoolbyname)){
                     $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
                     $this->appendArrayVar('headerParams', $css1);
                     $oddEven = 'even';
                     $myTable =& $this->getObject('htmltable', 'htmlelements');
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
            
          }else{
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
                  
                 $myTable->startRow();
                 $myTable->addCell("no records found");
                 $myTable->endRow();
                 
                // $myTable->startRow();
                // $myTable->addCell($SchoolLink->show());
                // $myTable->endRow(); 
          }
                 return $myTable->show();     
  }      
/*------------------------------------------------------------------------------*/
  /**
   *display all province schools by area
   */     
public  function schoolbyarea(){
      

           
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
  /**
   *display certain school details, if already exist in db or not
   */
public function schoolexisting($schoolbyname){
        
         $this->loadClass('link', 'htmlelements');      
         $SchoolLink = new link($this->uri(array('action' => 'captureschool', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Capture school details')));
         $SchoolLink->link = 'Capture School Details';
         
         $Link = new link($this->uri(array('action' => 'editoutput', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Edit school details')));
         $Link->link = 'Edit school details';
         
         $objLanguage =& $this->getObject('language', 'language');
         $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
         $this->objMainheading->type=3;
         $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_schooldet','marketingrecruitmentforum');
         
         $name  = $this->getSession('nameschool');
        
         if(!empty($schoolbyname)){
         
                foreach($schoolbyname as $sessCard){
                
                     //$css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
                     //$this->appendArrayVar('headerParams', $css1);
                     $oddEven = 'even';
                     $myTable =& $this->getObject('htmltable', 'htmlelements');
                     $myTable->cellspacing = '1';
                     $myTable->cellpadding = '2';
                     $myTable->border='0';
                     $myTable->width = '60%';
                     //$myTable->css_class = 'highlightrows';
                     //$myTable->row_attributes = " class = \"$oddEven\"";
                    
                    $myTable->startRow();
                    $myTable->addCell($this->objMainheading->show() .'<b>'. $name . '</b>');
                    $myTable->endRow();
              
                    $myTable->startRow();
                    $myTable->addCell("");
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell("<br />");
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell("");
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell('School Name', null,'top','left','header');
                    $myTable->addCell($sessCard['schoolname'],"25%", null, "left","widelink");
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell('Address', null,'top','left','header');
                    $myTable->addCell($sessCard['schooladdress'], "20%", null, "left","widelink");
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell('Telephone Number', null,'top','left','header');
                    $myTable->addCell($sessCard['telnumber'], "20%", null, "left","widelink");
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell('Fax Number', null,'top','left','header');
                    $myTable->addCell($sessCard['faxnumber'], "20%", null, "left","widelink"); 
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell('Email Address', null,'top','left','header');
                    $myTable->addCell($sessCard['email'], "20%", null, "left","widelink"); 
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell('Principal', null,'top','left','header');
                    $myTable->addCell($sessCard['principal'], "20%", null, "left","widelink");
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell('Guidance Teacher', null,'top','left','header');
                    $myTable->addCell($sessCard['guidanceteacher'], "20%", null, "left","widelink"); 
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    $myTable->addCell($Link->show());
                    $myTable->endRow();
                
                }  
            
          }else{
               //  $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
               //  $this->appendArrayVar('headerParams', $css1);
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '80%';
               //  $myTable->css_class = 'highlightrows';
               //  $myTable->row_attributes = " class = \"$oddEven\"";
                  
                 $myTable->startRow();
                 $myTable->addCell('<b>' . "NO RECORDS FOUND" . ' '.'FOR '. ' ' . '<u>'.$name.'</u>'.'</b>' . '<br />');
                 $myTable->endRow();
                 
                 $myTable->startRow();
                 $myTable->addCell($SchoolLink->show());
                 $myTable->endRow(); 
          }
                 return $myTable->show();     
  }      
/*------------------------------------------------------------------------------*/
}//end of class  
?>
