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
     
       //$css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
       //$this->appendArrayVar('headerParams','th.header');
       $oddEven = 'odd';
       $myTable =& $this->newObject('htmltable', 'htmlelements');
       $myTable->cellspacing = '1';
       $myTable->cellpadding = '2';
       $myTable->border='0';
       $myTable->width = '100%';
       $myTable->css_class = 'highlightrows';

       $myTable->startHeaderRow();
        $myTable->addHeaderCell('Date', null,'top','left','header');
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Postal Address', null,'top','left','header');
        $myTable->addHeaderCell('Postal Code', null,'top','left','header');
        $myTable->addHeaderCell('Telephone Number', null,'top','left','header');
        $myTable->addHeaderCell('Telephone Code', null,'top','left','header');
        $myTable->endHeaderRow();
     
        $rowcount = 0;
  
    foreach($results as $sessCard){
       
       
       $myTable->startRow();
      (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       $myTable->addCell($sessCard['date'],"6%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['schoolname'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['surname'], "10%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['name'],"10%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['postaddress'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['postcode'],"6%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['telnumber'],"10%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['telcode'],"6%", null, "left","$oddOrEven");
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
       $myTable->endRow();
       
       
       
       
        
   }  
   return $myTable->show();
}
/*------------------------------------------------------------------------------*/
  /**
   *all students from a certain school
   */
public  function allstudschool($school){
        
      if(isset($school)){
        
             //$css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
             //$this->appendArrayVar('headerParams', $css1);
             $oddEven = 'odd';
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '100%';
             $myTable->css_class = 'highlightrows';
             
          
      
            $myTable->startHeaderRow();
            $myTable->addHeaderCell('School Name', null,'top','left','header');
            $myTable->addHeaderCell('Surname', null,'top','left','header');
            $myTable->addHeaderCell('Name', null,'top','left','header');
            
            $rowcount = 0;
      
              foreach($school as $sessCard){
                 
                 $myTable->startRow();
                 (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell($sessCard['schoolname'],"15%", null, "left","$oddOrEven");
                 $myTable->addCell($sessCard['name'], "15%", null, "left","$oddOrEven");
                 $myTable->addCell($sessCard['surname'],"15%", null, "left","$oddOrEven");
                 
                 $myTable->endRow();
             }
      }else{
             //echo 'no records found';
             //$css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
             //$this->appendArrayVar('headerParams', $css1);
             $oddEven = 'odd';
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '30%';
             $myTable->css_class = 'highlightrows';
             $myTable->row_attributes = " class = \"$oddEven\"";
             
             $rowcount = 0;
             $myTable->startRow();
            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
             $myTable->addCell('NO RECORDS FOUND',"15%", null, "left","$oddOrEven");
             $myTable->endRow();
             
    }    
        return $myTable->show();
                 
  }      
/*------------------------------------------------------------------------------*/
  /**
   *display all students that qualify for an exemption
   */     
public  function  allwithexemption(){
      

           
      $results = $this->objstudcard->allstudsexemption();
      
      //$css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
      //   $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'odd';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        
        $myTable->addHeaderCell('Date', null,'top','left','header');
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Qualify for exemption', null,'top','left','header');
        
        $rowcount = 0;
  
    foreach($results as $sessCard){
     
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       $myTable->addCell($sessCard['date'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['schoolname'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['name'],"15%", null, "left","$oddOrEven");
       $myTable->addCell('Yes',"15%", null, "left","$oddOrEven");
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
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
      
        //$css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
         //$this->appendArrayVar('headerParams', $css1);
         $oddEven = 'odd';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Relevant Subject', null,'top','left','header');
        
        $rowcount = 0;
  
    foreach($results as $sessCard){
     
       
       
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       $myTable->addCell($sessCard['schoolname'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['name'],"15%", null, "left","$oddOrEven");
       $myTable->addCell('Yes',"15%", null, "left","$oddOrEven");
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
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
        
      //  $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
      //   $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'odd';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Faculty', null,'top','left','header');
        
        $rowcount = 0;
  
    foreach($results as $sessCard){
     
       
       
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       $myTable->addCell($sessCard['surname'], "10%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['name'],"10%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['faculty'],"10%", null, "left","$oddOrEven");
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
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
         $oddEven = 'odd';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        //$myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Course', null,'top','left','header');
        $myTable->addHeaderCell('Faculty', null,'top','left','header');
        
        
        $rowcount = 0;
  
    foreach($results as $sessCard){
     
       
       
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['surname'], "15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['name'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['course'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['faculty'],"15%", null, "left","$oddOrEven");
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
       $myTable->endRow();
        
   }  
   return $myTable->show();
  }
     
/*------------------------------------------------------------------------------*/
  /**
   *display all students by area
   */
public function studarea(){
       
       $results = $this->objstudcard->getstudbyarea();
       
        
   //     $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
   //      $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'odd';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Postal Address', null,'top','left','header');
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Area', null,'top','left','header');
        
        
        $rowcount = 0;
  
    foreach($results as $sessCard){
     
       
       
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       $myTable->addCell($sessCard['surname'], "15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['name'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['postaddress'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['studschoool'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessCard['area'],"15%", null, "left","$oddOrEven");
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
       $myTable->endRow();
        
   }  
   return $myTable->show();
   }     
/*------------------------------------------------------------------------------*/
  /**
   *display all students by area
   */
public function studsdcase(){
       
       $results = $this->objstudcard->sdcases();
        
   //     $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
   //      $this->appendArrayVar('headerParams', $css1);
         $oddEven = 'odd';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
         
      
  
        $myTable->startHeaderRow();
        //$myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('School Name', null,'top','left','header');
        $myTable->addHeaderCell('SD CASE', null,'top','left','header');
        
        $rowcount = 0;
        
  
    foreach($results as $sessSD){
     
       
       
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
       $myTable->addCell($sessSD['surname'], "15%", null, "left","$oddOrEven");
       $myTable->addCell($sessSD['name'],"15%", null, "left","$oddOrEven");
       $myTable->addCell($sessSD['schoolname'],"15%", null, "left","$oddOrEven");
       $myTable->addCell('Yes',"15%", null, "left","$oddOrEven"); 
//       $myTable->addCell($sessSD['sdcase'],"15%", null, "left","widelink"); 
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
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
             $oddEven = 'odd';
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '100%';
             $myTable->css_class = 'highlightrows';
             
          
      
            $myTable->startHeaderRow();
            $myTable->addHeaderCell('Surname', null,'top','left','header');
            $myTable->addHeaderCell('Name', null,'top','left','header');
//            $myTable->addHeaderCell('Postal Address', null,'top','left','header');
            $myTable->addHeaderCell('School Name', null,'top','left','header');
            $myTable->addHeaderCell('Faculty Name', null,'top','left','header');
            $myTable->addHeaderCell('Total Students', null,'top','left','header');
            
            
            $rowcount = 0;
  
            foreach($faculty as $sessSD){
             
               
               
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               //$myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
               $myTable->addCell($sessSD['surname'], "15%", null, "left","widelink");
               $myTable->addCell($sessSD['name'],"15%", null, "left","$oddOrEven");
//               $myTable->addCell($sessSD['postaddress'],"15%", null, "left","$oddOrEven");
               $myTable->addCell($sessSD['schoolname'],"15%", null, "left","$oddOrEven");
               $myTable->addCell($sessSD['faculty'],"15%", null, "left","$oddOrEven");
               $myTable->addCell($sessSD['totstud'],"15%", null, "left","$oddOrEven"); 
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;
               $myTable->endRow();
                
           }  
           return $myTable->show();
     }
   }     
/*------------------------------------------------------------------------------*/
  /**
   *all students from a certain school
   */
public  function searchID($idsearch){
  
  $this->loadClass('link', 'htmlelements');      
  $StudentCardLink = new link($this->uri(array('action' => 'capturestudcard', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Issue a student card')));
  $StudentCardLink->link = 'Capture Student Card';
  
  $StudentLink = new link($this->uri(array('action' => 'editoutput', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Issue a student card')));
  $StudentLink->link = 'Edit Student Details';
  
  $objLanguage =& $this->getObject('language', 'language');
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=3;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_norecords','marketingrecruitmentforum');
  
  $this->objheading =& $this->newObject('htmlheading','htmlelements');
  $this->objheading->type=3;
  $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_existingrec','marketingrecruitmentforum');
           
      if(!empty($idsearch)){
            //      $css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
                //  $this->appendArrayVar('headerParams', $css1);
                //  $oddEven = 'odd';
                  $myTable =& $this->newObject('htmltable', 'htmlelements');
                  $myTable->cellspacing = '1';
                  $myTable->cellpadding = '2';
                  $myTable->border='0';
                  $myTable->width = '70%';
              //    $myTable->css_class = 'highlightrows';
            
             
              foreach($idsearch as $sessCard){
                $exemp = $sessCard['exemption'];
                if($exemp){
                    $val = 'YES';
                }else{
                    $val = 'NO';
                }
                $idno  = $this->getSession('idno');
             
                $myTable->startRow();
                $myTable->addCell($this->objheading->show().'<b>'.$idno.'<b/>');
                $myTable->endRow();
                
                $myTable->startRow();
                $myTable->addCell("");
                $myTable->endRow();
                
                $myTable->startRow();
                $myTable->addCell("");
                $myTable->endRow();
                
                $myTable->startRow();
                $myTable->addCell("");
                $myTable->endRow();
             
              
                 $myTable->startRow();
                 $myTable->addCell("ID Number","15%", null, "left","widelink");
                 $myTable->addCell($sessCard['idnumber'],"15%", null, "left","widelink");
              
                 $myTable->startRow();
                 $myTable->addCell("Date","15%", null, "left","widelink");
                 $myTable->addCell($sessCard['date'],"15%", null, "left","widelink");
                 $myTable->endRow();
            
                 $myTable->startRow();
                 $myTable->addCell("Surname","15%", null, "left","widelink");
                 $myTable->addCell($sessCard['surname'],"15%", null, "left","widelink");
                 $myTable->endRow();
              
                  $myTable->startRow();
                  $myTable->addCell("Name","15%", null, "left","widelink");
                  $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
                  $myTable->endRow(); 
                  
                  $myTable->startRow();
                  $myTable->addCell("School Name","15%", null, "left","widelink");
                  $myTable->addCell($sessCard['schoolname'],"15%", null, "left","widelink");
                  $myTable->endRow();
                  
                  $myTable->startRow();
                  $myTable->addCell("Postal Address","15%", null, "left","widelink");
                  $myTable->addCell($sessCard['postaddress'],"15%", null, "left","widelink");
                  $myTable->endRow();
                  
                  $myTable->startRow();
                  $myTable->addCell("Postal Code","15%", null, "left","widelink");
                  $myTable->addCell($sessCard['postcode'],"15%", null, "left","widelink");
                  $myTable->endRow();
                  
                  $myTable->startRow();
                  $myTable->addCell("Telephone Number","15%", null, "left","widelink");
                  $myTable->addCell($sessCard['telnumber'],"15%", null, "left","widelink");
                  $myTable->endRow();
                  
                  $myTable->startRow();
                  $myTable->addCell("Telephone Code","15%", null, "left","widelink");
                  $myTable->addCell($sessCard['telcode'],"15%", null, "left","widelink");
                  $myTable->endRow();
                  
                  $myTable->startRow();
                  $myTable->addCell("Exemption","15%", null, "left","widelink");
                  $myTable->addCell($val,"15%", null, "left","widelink");
                  $myTable->endRow();
                  
                  $myTable->startRow();
                  $myTable->addCell("Faculty","15%", null, "left","widelink");
                  $myTable->addCell($sessCard['faculty'],"15%", null, "left","widelink");
                  $myTable->endRow();
                  
                  $myTable->startRow();
                  $myTable->addCell("Course","15%", null, "left","widelink");
                  $myTable->addCell($sessCard['course'],"15%", null, "left","widelink");
                  $myTable->endRow();
                  
                  $myTable->startRow();
                  $myTable->addCell($StudentLink->show());
                  $myTable->endRow();
          }
      }else{

              /*$css1 = '<link rel="stylesheet" type="text/css" href="modules/marketingrecruitmentforum/resources/mrsf.css" />';
             $this->appendArrayVar('headerParams', $css1);
             $oddEven = 'odd';*/
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '30%';
             
             $idno  = $this->getSession('idno');
             
             $myTable->startRow();
             $myTable->addCell($this->objMainheading->show().'<b>'.$idno.'<b/>');
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->endRow();
             
             $myTable->startRow();
             $myTable->addCell($StudentCardLink->show());
             $myTable->endRow();
          }    

            return $myTable->show();     
  }      
/*-----------------------------------------------------------------------------------------------------*/            
}//end of class  
?>
