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
   *create a checkbox for selection when sending a letter
   */
   $this->loadClass('checkbox','htmlelements');
   $objElement = new checkbox('post');  // this will checked
   $objElement->extra = ' onClick="document.addressgen.submit()"';
   $check = $objElement->show();     
/*------------------------------------------------------------------------------*/
 /**
  * create table to display all addy's in
  */   
      $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
      $results = $this->objstudcard->getallstudaddy();
  
       //create table to hold data containing student address details
       $oddEven = 'even';
       $myTable =& $this->newObject('htmltable', 'htmlelements');
       $myTable->cellspacing = '1';
       $myTable->cellpadding = '2';
       $myTable->border='0';
       $myTable->width = '100%';
       $myTable->css_class = 'highlightrows';
   
        $myTable->startHeaderRow();
        $myTable->addHeaderCell('Surname', null,'top','left','header');
        $myTable->addHeaderCell('Name', null,'top','left','header');
        $myTable->addHeaderCell('Address Details', null,'top','left','header');
    //    $myTable->addHeaderCell('Post Letter', null,'top','left','header');
        $myTable->endHeaderRow();
     
        $rowcount = '0';
  
   // foreach($results as $sessCard){
       for($i=0; $i< count($results); $i++){
       
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       $myTable->addCell($results[$i]->SURNAME, "10%", null, "left","$oddOrEven");
       $myTable->addCell($results[$i]->NAME,"10%", null, "left","$oddOrEven");
       $myTable->addCell($results[$i]->POSTADDRESS,"15%", null, "left","$oddOrEven");
      // $myTable->addCell($check,"15%", null, "left","$oddOrEven");
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
       $myTable->endRow();
        
   }
      $this->setSession('results',$results);
   
/*------------------------------------------------------------------------------*/
   $objForm = new form('addressgen',$this->uri(array('action'=>'postletter')));
   $objForm->displayType = 3;
   $objForm->addToForm($myTable->show());
    
   
/*------------------------------------------------------------------------------*/
  /**
   *display all info on screen
   */
   echo   $this->objMainheading->show(); 
   echo   '<br />'  . '<br />' .$this->objheading->show(); 
   echo   '<br />'  . '<br />'  . $objForm->show();;  
?>
