<?php

/**template to display all information captured**/
  
  /**
   *load all classes and create all objects
   */
    $this->loadClass('button','htmlelements');  
    $this->loadClass('tabbedbox', 'htmlelements');
    $this->loadClass('link', 'htmlelements');

  /*----------------------------------------------------------------------------------------*/     
  
  /**
   *create all form language items
   */           
    $submit = $this->objLanguage->languageText('word_submit');
    $str1 = ucfirst($submit);
    
    $editstud = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editstud', 'marketingrecruitmentforum');
    $editslu = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editslu', 'marketingrecruitmentforum');
    $editschool = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editsschool1', 'marketingrecruitmentforum');

    $editStudLink = new link($this->uri(array('action' => 'editstudcard', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editStudLink->link = $editstud;
    
    $editSLUlink = new link($this->uri(array('action' => 'editsluactivity', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editSLUlink->link = $editslu;
    
    $editSchoollink = new link($this->uri(array('action' => 'editschool', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editSchoollink->link = $editschool;
  /*----------------------------------------------------------------------------------------*/ 

 /**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_output','marketingrecruitmentforum');
 /*----------------------------------------------------------------------------------------*/ 
  
  /**
   *create all form buttons
   */
        
  $this->objSubmitstudcard  = new button('submitstudcard', $str1);
  $this->objSubmitstudcard->setToSubmit();
  /*----------------------------------------------------------------------------------------*/
    //store session sluactivitydata values into an array 
    //array used to loop thru information contained 
    $sessionsluactivity [] = $this->getSession('sluactivitydata');

  /**
   *display all sluactivity info contained session variable
   */     
        if(!empty($sessionsluactivity)){
        //Create table to display sluactivity details in session  
              $objactivityTable =& $this->newObject('htmltable', 'htmlelements');
              $objactivityTable->cellspacing = '2';
              $objactivityTable->cellpadding = '2';
              $objactivityTable->border='0';
              $objactivityTable->width = '70%';
            
            foreach($sessionsluactivity as $sesSLU){
               $rowcount = 0;
               
              $objactivityTable->startRow();
              (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
              $objactivityTable->addCell('Date',"15%", null, "left",$oddOrEven);
              $objactivityTable->addCell(strtoupper($sesSLU['date']),"15%", null, "left",$oddOrEven);
              $objactivityTable->row_attributes = " class = \"$oddOrEven\"";
              $rowcount++;
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
              $objactivityTable->addCell('Activity',"15%", null, "left",$oddOrEven);
              $objactivityTable->addCell(strtoupper($sesSLU['activity']),"15%", null, "left",$oddOrEven);
              $objactivityTable->row_attributes = " class = \"$oddOrEven\"";
              $rowcount++;
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
              $objactivityTable->addCell('School Name',"15%", null, "left",$oddOrEven);
              $objactivityTable->addCell(strtoupper($sesSLU['schoolname']),"15%", null, "left",$oddOrEven);
              $objactivityTable->row_attributes = " class = \"$oddOrEven\"";
              $rowcount++;
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
              $objactivityTable->addCell('Area',"15%", null, "left",$oddOrEven);
              $objactivityTable->addCell(strtoupper($sesSLU['area']),"15%", null, "left",$oddOrEven);
              $objactivityTable->row_attributes = " class = \"$oddOrEven\"";
              $rowcount++;
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
              $objactivityTable->addCell('Province',"15%", null, "left",$oddOrEven);
              $objactivityTable->addCell(strtoupper($sesSLU['province']),"15%", null, "left",$oddOrEven);
              $objactivityTable->row_attributes = " class = \"$oddOrEven\"";
              $rowcount++;
              $objactivityTable->endRow();
            }
  
        }
/*----------------------------------------------------------------------------------------*/  

    /**
     *create tabbed boxes to place elements in
     */
    $strelements1 =   $objactivityTable->show();
    
    $objslutab = new tabbedbox();
    $objslutab->addTabLabel('UWC Outreach Activities');
    $objslutab->addBoxContent("<div align=\"right\">" .$editSLUlink->show() . "</div>".'<br />' . $strelements1);
   
   $stringval = $objslutab->show();
  /**
    *create a form to place all elements in
    */
      
      $objForm = new form('outputdata',$this->uri(array('action'=>'submitactivitydata','submitmsg' => 'yes')));
      $objForm->displayType = 3;
      $objForm->addToForm($this->objMainheading->show().'<br/>'.$stringval . '<br />' . "<div align=left>".$this->objSubmitstudcard->show()."</div>"); 
      echo  $objForm->show();
 
?>
