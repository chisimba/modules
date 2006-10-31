<?php
//template used to create the address generator for all students that filled info cards

 /**
  *create form heading
  */
 $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
 $this->objMainheading->type=1;
 $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_addressgen','marketingrecruitmentforum');
 
 $this->objheading =& $this->newObject('htmlheading','htmlelements');
 $this->objheading->type=3;
 $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_selectaddy','marketingrecruitmentforum');
 
/*------------------------------------------------------------------------------*/
 /**
  * create table to display all addy's in
  */   
      $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
   $results = $this->objstudcard->getallstudaddy();
     
     
    
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
        $myTable->addHeaderCell('Address Details', null,'top','left','header');
        $myTable->endHeaderRow();
     
        $rowcount = '0';
  
    foreach($results as $sessCard){
     
       $oddOrEven = ($rowcount == 0) ? "odd" : "even";
       
       $myTable->startRow();
       $myTable->addCell($sessCard['surname'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['name'],"15%", null, "left","widelink");
       $myTable->addCell($sessCard['postaddress'],"15%", null, "left","widelink");
       $myTable->endRow();
        
   }
/*------------------------------------------------------------------------------*/
  /**
   *display all info on screen
   */
   echo   $this->objMainheading->show(); 
   echo   '<br />'  . '<br />' .$this->objheading->show(); 
   echo   '<br />'  . '<br />'  . $myTable->show();  
?>
