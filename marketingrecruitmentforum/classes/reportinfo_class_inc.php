<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
* This object generates data used to display search results for student card (SLU) used in for reports 
* @package 
* @category sems
* @copyright 2005, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class reportinfo extends object{ 

  /**
   * Standard init funcion
   * @param void
   * @return void
   */            
 
public	function init()
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
 * Method used to create a table to display all students that are sd cases
 * @param string $result,contains resulset of sd cases by calling the function sdcases() in dbstudcard class
 * loop thorugh contents of $result and create table rows
 * @return obj $myTable  
 */  
public function displaysdcases(){
  
         $results = $this->objstudcard->sdcases($where = 'where SDCASE = 1 and EXEMPTION = 0 ');
         
         
         /**
          * define all language item text
          */                    
         $surname1 = $this->objLanguage->languageText('word_surname');
         $surname = ucfirst($surname1);
         $name1 = $this->objLanguage->languageText('word_name');
         $name = ucfirst($name1);
         $sdcase = $this->objLanguage->languageText('mod_marketingrecruitmentforum_sdcase', 'marketingrecruitmentforum');
               
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '80%';
         $myTable->css_class = 'highlightrows';
  
         $myTable->startHeaderRow();
         $myTable->addHeaderCell($surname, null,'top','left','header');
         $myTable->addHeaderCell($name, null,'top','left','header');
         $myTable->addHeaderCell($sdcase, null,'top','left','header');
         $myTable->endHeaderRow();
     
         $rowcount = '0';
  
  
  //  foreach($results as $sessCard){
      for($i=0; $i< count($results); $i++){
      
       
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SURNAME, "15%", null, "left","$oddOrEven");
         $myTable->addCell($results[$i]->NAME, "15%", null, "left","$oddOrEven");
         $myTable->addCell('YES', "15%", null, "left",$oddOrEven);
         $myTable->row_attributes = " class = \"$oddOrEven\"";
         $rowcount++;
         $myTable->endRow();
        
   }  
   
   return $myTable->show();
}
/*------------------------------------------------------------------------------*/ 
/**
 * Method to create a table to display all students qualifying for entry
 * @param string $result,contains resulset of students qualified by calling the function getstudqualify() in dbstudcard class
 * loop thorugh contents of $result and create table rows
 * @return obj $myTable  
 */   
public function entryQualification(){
    
         $results = $this->objstudcard->getstudqualify($where = 'where sdcase = 0 and exemption = 1');
        // var_dump($results);
         /**
          * define all language item text
          */                    
         $surname1 = $this->objLanguage->languageText('word_surname');
         $surname = ucfirst($surname1);
         $name1 = $this->objLanguage->languageText('word_name');
         $name = ucfirst($name1);
         $exemption = $this->objLanguage->languageText('word_exemption');
         $relsubject = $this->objLanguage->languageText('mod_marketingrecruitmentforum_relevantsubject','marketingrecruitmentforum');
         $relsubject1 = ucfirst($relsubject);
     
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
    
  
         $myTable->startHeaderRow();
         $myTable->addHeaderCell('School Name', null,'top','left','header');
         $myTable->addHeaderCell('Grade', null,'top','left','header');
         $myTable->addHeaderCell($surname, null,'top','left','header');
         $myTable->addHeaderCell($name, null,'top','left','header');
         $myTable->addHeaderCell($exemption, null,'top','left','header');
         //$myTable->addHeaderCell($relsubject1, null,'top','left','header');
         $myTable->endHeaderRow();
     
         $rowcount = '0';
 
             
      
//    foreach($results as $sessCard){
       for($i=0; $i< count($results); $i++){
     
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SCHOOLNAME,"15%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->GRADE,"15%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->SURNAME,"15%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->NAME, "15%", null, "left",$oddOrEven);
         $myTable->addCell('YES', "15%", null, "left",$oddOrEven);
         //$myTable->addCell('YES', "15%", null, "left",$oddOrEven);
         $myTable->row_attributes = " class = \"$oddOrEven\"";
         $rowcount++;
         $myTable->endRow();
        
   }  
   
   return $myTable->show(); 
}
/*------------------------------------------------------------------------------*/ 
/**
 * Method to create a table to display all student details interested in a particular faculty
 * @param string $result,contains resultset of all students interested in a certain faculty
 * loop thorugh contents of $result and create table rows
 * @return obj $myTable  
 */
public function facultyinterest(){
    
         $results = $this->objstudcard->getallstudinfo();
         
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
         $myTable->width = '80%';
         $myTable->css_class = 'highlightrows';
  
         $myTable->startHeaderRow();
         $myTable->addHeaderCell($surname, null,'top','left','header');
         $myTable->addHeaderCell($name, null,'top','left','header');
         $myTable->addHeaderCell($faculty, null,'top','left','header');
         $myTable->endHeaderRow();
     
         $rowcount = '0';
  
    foreach($results as $sessCard){
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($sessCard['name'],"15%", null, "left",$oddOrEven);
         $myTable->addCell($sessCard['surname'], "15%", null, "left",$oddOrEven);
         $myTable->addCell($sessCard['faculty'], "15%", null, "left",$oddOrEven);
         $myTable->row_attributes = " class = \"$oddOrEven\"";
         $rowcount++;
         $myTable->endRow();
   }  
   
   return $myTable->show(); 
}
/*------------------------------------------------------------------------------*/

}//end of class
?>
