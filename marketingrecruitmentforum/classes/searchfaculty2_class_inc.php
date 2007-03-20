<?php
/** security check - must be included in all scripts**/
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* This object generates data used to display search results for student card (faculty) 
* @package 
* @category sems
* @copyright 2005, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/

class searchfaculty2 extends object{ 
/**
 * Standard init function
 * @param void
 * @return voic
 */     
	function init()
	{
  	 try{
        
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
 * Method used to create a table to display all students that completed information cards for a specific faculty
 * @param array obj $facultyval,contains resulset of student details
 * loop thorugh contents of $facultyval and create table rows
 * @return obj $myTable  
 */ 
  
public  function studentsbyfaculty2($facultyval2){

      if(isset($facultyval2)){
     
               
            /**
              * define all language item text
              */
                                  
               $surname1 = $this->objLanguage->languageText('word_surname');
               $surname = ucfirst($surname1);
               $name1 = $this->objLanguage->languageText('word_name');
               $name = ucfirst($name1);  
               $faculty = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultyname','marketingrecruitmentforum');
 
               $oddEven = 'even';
               $myTable =& $this->newObject('htmltable', 'htmlelements');
               $myTable->cellspacing = '1';
               $myTable->cellpadding = '2';
               $myTable->border='0';
               $myTable->width = '100%';
               $myTable->css_class = 'highlightrows';
             
               $myTable->startHeaderRow();
               $myTable->addHeaderCell($surname, null,'top','left','header');
               $myTable->addHeaderCell($name, null,'top','left','header');
               $myTable->addHeaderCell($faculty, null,'top','left','header');
               $myTable->endHeaderRow();
                
               $rowcount = '0';
          
            //foreach($facultyval as $sessFac){
            for($i=0; $i< count($facultyval2); $i++){
             
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               $myTable->addCell($facultyval2[$i]->SURNAME,"20%", null, "left",$oddOrEven);
               $myTable->addCell($facultyval2[$i]->NAME, "20%", null, "left",$oddOrEven);
               $myTable->addCell($facultyval2[$i]->FACULTY2, "20%", null, "left",$oddOrEven);
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;
               $myTable->endRow();
                
            }  
        }else{
    
                 $oddEven = 'even';
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
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }  
            
               return $myTable->show();
               
   //   }
                 
}      
/*------------------------------------------------------------------------------*/     
/**
 * Method used to create a table to display all students that qualify for exemption for a specific FACULTY2
 * @param array obj $FACULTY2exmp,contains resulset of student details
 * loop through contents of $FACULTY2exmp and create table rows
 * @return obj $myTable  
 */    
public function exemptionbyfaculty2($facultyexmp2 = NULL){
        
       if(isset($facultyexmp2)){
               /**
              * define all language item text
              */
                                  
               $surname = $this->objLanguage->languageText('word_surname');
               $name = $this->objLanguage->languageText('word_name');
               $schoolname = $this->objLanguage->languageText('phrase_schoolname');
               $schoolname1 = ucfirst($schoolname);
               $exemption = $this->objLanguage->languageText('word_exemption');  
               $FACULTY2 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultyname','marketingrecruitmentforum');
   
               $oddEven = 'even';
               $myTable =& $this->newObject('htmltable', 'htmlelements');
               $myTable->cellspacing = '1';
               $myTable->cellpadding = '2';
               $myTable->border='0';
               $myTable->width = '100%';
               $myTable->css_class = 'highlightrows';
               
               $myTable->startHeaderRow();
               $myTable->addHeaderCell(ucfirst($surname), null,'top','left','header');
               $myTable->addHeaderCell(ucfirst($name), null,'top','left','header');
               $myTable->addHeaderCell($schoolname1, null,'top','left','header');
               $myTable->addHeaderCell($exemption, null,'top','left','header');
               $myTable->addHeaderCell($FACULTY2, null,'top','left','header');
               $myTable->endHeaderRow();
             
               $rowcount = '0';
          
            for($i=0; $i< count($facultyexmp2); $i++){
             
            
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               $myTable->addCell($facultyexmp2[$i]->SURNAME,"20%", null, "left",$oddOrEven);
               $myTable->addCell($facultyexmp2[$i]->NAME, "20%", null, "left",$oddOrEven);
               $myTable->addCell($facultyexmp2[$i]->SCHOOLNAME, "20%", null, "left",$oddOrEven);
               $myTable->addCell('YES', "20%", null, "left",$oddOrEven);
               $myTable->addCell($facultyexmp2[$i]->FACULTY2, "20%", null, "left",$oddOrEven);
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;
               $myTable->endRow();
                
            } 
         }else{
    
                 $oddEven = 'even';
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
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }   
            
            return $myTable->show();
            
  //     }
                 
}      
/*------------------------------------------------------------------------------*/    
/**
 * Method used to create a table to display all students that completed information cards and have the relevant subjects for FACULTY2 entered 
 * @param array obj $facsubj,contains resulset of student details...all students with relevant subjects
 * loop thorugh contents of $facsubj and create table rows
 * @return obj $myTable  
 */
public  function relsubjbyfaculty2($facsubj2 = NULL){
      
         if(isset($facsubj2)){
                 /**
                   * create all language item text
                   */                                     
                 $surname = $this->objLanguage->languageText('word_surname');
                 $name = $this->objLanguage->languageText('word_name');
                 $schoolname = $this->objLanguage->languageText('phrase_schoolname');
                 $schoolname1 = ucfirst($schoolname);
                 $relsubject = $this->objLanguage->languageText('mod_marketingrecruitmentforum_relevantsubject','marketingrecruitmentforum'); 
                 $relsubject1 = ucfirst($relsubject);  
                 $FACULTY2 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_FACULTY2name','marketingrecruitmentforum'); 
                 
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '100%';
                 $myTable->css_class = 'highlightrows';
               
                 $myTable->startHeaderRow();
                 $myTable->addHeaderCell($surname, null,'top','left','header');
                 $myTable->addHeaderCell($name, null,'top','left','header');
                 $myTable->addHeaderCell($schoolname1, null,'top','left','header');
                 $myTable->addHeaderCell($relsubject1, null,'top','left','header');
                 $myTable->addHeaderCell($FACULTY, null,'top','left','header');
                 $myTable->endHeaderRow();
                
                
                 $rowcount = '0';
          
           // foreach($facsubj as $sessFac){
           for($i=0; $i< count($facsubj2); $i++){
             
                 $myTable->startRow();
                 (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell($facsubj2[$i]->SURNAME,"20%", null, "left",$oddOrEven);
                 $myTable->addCell($facsubj2[$i]->NAME, "20%", null, "left",$oddOrEven);
                 $myTable->addCell($facsubj2[$i]->SCHOOLNAME, "20%", null, "left",$oddOrEven);
                 $myTable->addCell('YES', "20%", null, "left",$oddOrEven);
                 $myTable->addCell($facsubj2[$i]->FACULTY2, "20%", null, "left",$oddOrEven);
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
                
            } 
            
        }else{
    
                 $oddEven = 'even';
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
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }  
             
                 return $myTable->show();
   //       }
                 
  }      
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display student details per course within FACULTY2
 * @param array obj $faccourse,contains resulset of student details...all students with specific course
 * loop thorugh contents of $facsubj and create table rows
 * @return obj $myTable  
 */
public  function coursebyfaculty2($faccourse2){
      
         if(isset($faccourse2)){
                  /**
                   * create all language item text
                   */                                     
                  $surname = $this->objLanguage->languageText('word_surname');
                  $name = $this->objLanguage->languageText('word_name');
                  $schoolname = $this->objLanguage->languageText('phrase_schoolname');
                  $schoolname1 = ucfirst($schoolname);
                  $course = $this->objLanguage->languageText('word_course');
                  $FACULTY = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultyname','marketingrecruitmentforum');
                  
                  $oddEven = 'even';
                  $myTable =& $this->newObject('htmltable', 'htmlelements');
                  $myTable->cellspacing = '1';
                  $myTable->cellpadding = '2';
                  $myTable->border='0';
                  $myTable->width = '100%';
                  $myTable->css_class = 'highlightrows';
                
              
          
                  $myTable->startHeaderRow();
                  $myTable->addHeaderCell(ucfirst($surname), null,'top','left','header');
                  $myTable->addHeaderCell(ucfirst($name), null,'top','left','header');
                  $myTable->addHeaderCell(ucfirst($schoolname), null,'top','left','header');
                  $myTable->addHeaderCell($course , null,'top','left','header');
                  $myTable->addHeaderCell($FACULTY, null,'top','left','header');
                  $myTable->endHeaderRow();
                
                
                  $rowcount = '0';
          
            // foreach($faccourse as $sessFac){
            for($i=0; $i< count($faccourse2); $i++){
             
               
               
                 $myTable->startRow();
                 (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell($faccourse2[$i]->SURNAME,"20%", null, "left",$oddOrEven);
                 $myTable->addCell($faccourse2[$i]->NAME, "20%", null, "left",$oddOrEven);
                 $myTable->addCell($faccourse2[$i]->SCHOOLNAME, "20%", null, "left",$oddOrEven);
                 $myTable->addCell($faccourse2[$i]->COURSE2, "20%", null, "left",$oddOrEven);
                 $myTable->addCell($faccourse2[$i]->FACULTY2, "20%", null, "left",$oddOrEven);
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
                
            }
        }else{
    
                 $oddEven = 'even';
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
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }    
            
                 return $myTable->show();
                 
  //      }
                 
}   
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display all sd cases per FACULTY2
 * @param array obj $facsdcase,contains resulset of all students within a FACULTY2 that qaulify as an sdcase
 * loop thorugh contents of $facsdcase and create table rows
 * @return obj $myTable  
 */
public  function sdcasebyfaculty2($facsdcase2){
  
         if(isset($facsdcase2)){
         
                 /**
                   * create all language item text
                   */                                     
                 $surname = $this->objLanguage->languageText('word_surname');
                 $name = $this->objLanguage->languageText('word_name');
                 $schoolname = $this->objLanguage->languageText('phrase_schoolname');
                 $schoolname1 = ucfirst($schoolname);
                 $sdcase = $this->objLanguage->languageText('mod_marketingrecruitmentforum_sdcase', 'marketingrecruitmentforum');
                 $FACULTY2 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultyname','marketingrecruitmentforum');
                   
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '100%';
                 $myTable->css_class = 'highlightrows';
               
                 $myTable->startHeaderRow();
                 $myTable->addHeaderCell(ucfirst($surname), null,'top','left','header');
                 $myTable->addHeaderCell(ucfirst($name), null,'top','left','header');
                 $myTable->addHeaderCell($schoolname1, null,'top','left','header');
                 $myTable->addHeaderCell($sdcase, null,'top','left','header');
                 $myTable->addHeaderCell($FACULTY2, null,'top','left','header');
                 $myTable->endHeaderRow();
                
                
                 $rowcount = '0';
          
           // foreach($facsdcase as $sessFac){
           for($i=0; $i< count($facsdcase2); $i++){
               
                 $myTable->startRow();
                 (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell($facsdcase2[$i]->SURNAME,"20%", null, "left",$oddOrEven);
                 $myTable->addCell($facsdcase2[$i]->NAME, "20%", null, "left",$oddOrEven);
                 $myTable->addCell($facsdcase2[$i]->SCHOOLNAME, "20%", null, "left",$oddOrEven);
                 $myTable->addCell('YES', "20%", null, "left",$oddOrEven);
                 $myTable->addCell($facsdcase2[$i]->FACULTY2, "20%", null, "left",$oddOrEven);
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
                
           }  
      }else{
    
                 $oddEven = 'even';
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
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }  
           
                 return $myTable->show();
                 
  //      }
                 
  }   
/*------------------------------------------------------------------------------*/  
}//end class
?>
