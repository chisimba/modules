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
    $editschool = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editsschool', 'marketingrecruitmentforum');

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
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_changes','marketingrecruitmentforum');
 /*----------------------------------------------------------------------------------------*/ 
  
  /**
   *create all form buttons
   */
        
  $this->objSubmitstudcard  = new button('submitstudcard', $str1);
  $this->objSubmitstudcard->setToSubmit();
  /*----------------------------------------------------------------------------------------*/
   
   /**
    *determine if session variables containing student card data, activities data and school data is empty or not 
    *if !empty then user has not submited information as yet
    *if empty, information submitted    
    */           
     
   // $sessionstudcard [] = $this->getSession('studentdata');
    $sessionsluactivity [] = $this->getSession('sluactivitydata');
    $sessionsschoolist [] = $this->getSession('schoolvalues');
    
   if((!empty($sessionstudcard) )|| (!empty($sessionsluactivity)) || (!empty($sessionsschoolist))){
   
          $hasSubmitted = 'no';
   }else{
          $hasSubmitted = 'yes';
   }        
  /*----------------------------------------------------------------------------------------*/        
  /**
   *display all student card info contained in the session variable
   */      
   
    /**
   *display all student card info contained in the session variable
   */      
  //$sessionstudcard = array(); 
  //$sessionstudcard[]= $this->getSession('studentdata');
  $idnum = $this->getSession('idno');
  
  $idsearch  = $this->dbstudentcard->getstudbyid($idnum);         
  $this->setSession('idsearch',$idsearch);
      if(!empty($idsearch) ){
      //Create table to display student details in session
            $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
            //$objstudcardTable =& $this->newObject('htmltable', 'htmlelements');
            $objstudcardTable->cellspacing = '2';
            $objstudcardTable->cellpadding = '2';
            $objstudcardTable->cellwidth = '10';
            $objstudcardTable->border='0';
            $objstudcardTable->width = '80%';
  
            
  
            foreach($idsearch as $sesStuddata){
     
                if($sesStuddata['exemption'] == 1){
                    $exemptionval = 'yes';
                }else{
                      $exemptionval = 'no';
                }
                
                if($sesStuddata['relevantsubject'] == 1){
                    $relsubjval = 'yes';
                }else{
                    $relsubjval = 'no';
                }
                if($sesStuddata['sdcase'] == 1){
                    $sdvalues = 'yes';
                }else{
                    $sdvalues = 'no';
                }    
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('ID Number');
                $objstudcardTable->addCell($idnum);
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Date');
                $objstudcardTable->addCell($sesStuddata['date']);
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Shool Name' );
                $objstudcardTable->addCell(strtoupper($sesStuddata['schoolname']));   //not showing
                $objstudcardTable->endRow();
  
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Surname');
                $objstudcardTable->addCell(strtoupper($sesStuddata['surname']));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Name');
                $objstudcardTable->addCell(strtoupper($sesStuddata['name']));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Postal Address');
                $objstudcardTable->addCell(strtoupper($sesStuddata['postaddress']));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Postal Code');
                $objstudcardTable->addCell(strtoupper($sesStuddata['postcode']));
                 $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Telephone Number');
                $objstudcardTable->addCell(strtoupper($sesStuddata['telnumber']));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Telephone Code');
                $objstudcardTable->addCell(strtoupper($sesStuddata['telcode']));//, '', '', '', $oddOrEven);
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Qualify for Exemption');
                $objstudcardTable->addCell(strtoupper($exemptionval));//, '', '', '', $oddOrEven);
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Faculty Name');
                $objstudcardTable->addCell(strtoupper($sesStuddata['faculty']));//, '', '', '', $oddOrEven);
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Course Interested In');
                $objstudcardTable->addCell(strtoupper($sesStuddata['course']));//, '', '', '', $oddOrEven);
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('Relevant Subject');
                $objstudcardTable->addCell(strtoupper($relsubjval));//, '', '', '', $oddOrEven);
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell('SD Case');
                $objstudcardTable->addCell(strtoupper($sdvalues));//, '', '', '', $oddOrEven);
                $objstudcardTable->endRow();
            }
      }else{
            $sessionstudcard [] = $this->getSession('studentdata');
      //if(!empty($sessionstudcard)){
          //Create table to display student details in session
              $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
              $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');
              $objstudcardTable->cellspacing = '2';
              $objstudcardTable->cellpadding = '2';
              $objstudcardTable->cellwidth = '10';
              $objstudcardTable->border='0';
              $objstudcardTable->width = '80%';
    
              
    
              foreach($sessionstudcard as $sesStuddata){
       
                  if($sesStuddata['exemption'] == 1){
                      $exemptionval = 'yes';
                  }else{
                        $exemptionval = 'no';
                  }
                  
                  if($sesStuddata['relevantsubject'] == 1){
                      $relsubjval = 'yes';
                  }else{
                      $relsubjval = 'no';
                  }
                  if($sesStuddata['sdcase'] == 1){
                      $sdvalues = 'yes';
                  }else{
                      $sdvalues = 'no';
                  }    
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('ID Number');
                  $objstudcardTable->addCell($idnum);
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Date');
                  $objstudcardTable->addCell($sesStuddata['date']);
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Shool Name' );
                  $objstudcardTable->addCell(strtoupper($sesStuddata['schoolname']));   //not showing
                  $objstudcardTable->endRow();
    
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Surname');
                  $objstudcardTable->addCell(strtoupper($sesStuddata['surname']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Name');
                  $objstudcardTable->addCell(strtoupper($sesStuddata['name']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Postal Address');
                  $objstudcardTable->addCell(strtoupper($sesStuddata['postaddress']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Postal Code');
                  $objstudcardTable->addCell(strtoupper($sesStuddata['postcode']));
                   $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Telephone Number');
                  $objstudcardTable->addCell(strtoupper($sesStuddata['telnumber']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Telephone Code');
                  $objstudcardTable->addCell(strtoupper($sesStuddata['telcode']));//, '', '', '', $oddOrEven);
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Qualify for Exemption');
                  $objstudcardTable->addCell(strtoupper($exemptionval));//, '', '', '', $oddOrEven);
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Faculty Name');
                  $objstudcardTable->addCell(strtoupper($sesStuddata['faculty']));//, '', '', '', $oddOrEven);
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Course Interested In');
                  $objstudcardTable->addCell(strtoupper($sesStuddata['course']));//, '', '', '', $oddOrEven);
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Relevant Subject');
                  $objstudcardTable->addCell(strtoupper($relsubjval));//, '', '', '', $oddOrEven);
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('SD Case');
                  $objstudcardTable->addCell(strtoupper($sdvalues));//, '', '', '', $oddOrEven);
                  $objstudcardTable->endRow();
            }
      }
      
      
/*----------------------------------------------------------------------------------------*/
  /**
   *display all sluactivity info contained session variable
   */     
  //  $sessionsluactivity [] = $this->getSession('sluactivitydata');
        if(!empty($sessionsluactivity)){
        //Create table to display sluactivity details in session  
              $objactivityTable =& $this->newObject('htmltable', 'htmlelements');
              $objactivityTable->cellspacing = '2';
              $objactivityTable->cellpadding = '2';
              $objactivityTable->border='0';
              $objactivityTable->width = '105%';
            
            foreach($sessionsluactivity as $sesSLU){
            
              $objactivityTable->startRow();
              $objactivityTable->addCell('Date');
              $objactivityTable->addCell(strtoupper($sesSLU['date']));//, '', '', '', $oddOrEven);
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              $objactivityTable->addCell('Activity');
              $objactivityTable->addCell(strtoupper($sesSLU['activity']));//, '', '', '', $oddOrEven);
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              $objactivityTable->addCell('School Name');
              $objactivityTable->addCell(strtoupper($sesSLU['schoolname']));//, '', '', '', $oddOrEven);
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              $objactivityTable->addCell('Area');
              $objactivityTable->addCell(strtoupper($sesSLU['area']));//, '', '', '', $oddOrEven);
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              $objactivityTable->addCell('Province');
              $objactivityTable->addCell(strtoupper($sesSLU['province']));//, '', '', '', $oddOrEven);
              $objactivityTable->endRow();
            }
              /** show the captured data**/  
        }
/*----------------------------------------------------------------------------------------*/  
  /**
   *display all school list info contained in session
   */     
        //$this->objschool  = & $this->getObject('searchschools','marketingrecruitmentforum');
        $namevalue = $this->getSession('nameschool');
        $school = $this->dbschoollist->getschoolbyname($namevalue);
        $this->setSession('schoovals',$school);
        
        
        if(!empty($school)){
        //Create table to display school list details in session  
            $objschoolTable =& $this->newObject('htmltable', 'htmlelements');        
            $objschoolTable->cellspacing = '2';
            $objschoolTable->cellpadding = '2';
            $objschoolTable->border='0';
            $objschoolTable->width = '70%';
          
          foreach($school as $sesschool){
          
                        
            $objschoolTable->startRow();
            $objschoolTable->addCell('School Name');
            $objschoolTable->addCell(strtoupper($sesschool['schoolname']));//, '', '', '', $oddOrEven);
            //$objschoolTable->addCell("<div align=\"right\">" .$editSchoollink->show() . "</div>");
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('School Address');
            $objschoolTable->addCell(strtoupper($sesschool['schooladdress']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Telephone Number');
            $objschoolTable->addCell(strtoupper($sesschool['telnumber']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Fax Number');
            $objschoolTable->addCell(strtoupper($sesschool['faxnumber']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Email');
            $objschoolTable->addCell(strtoupper($sesschool['email']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Principal');
            $objschoolTable->addCell(strtoupper($sesschool['principal']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Grade 12 guidance teacher');
            $objschoolTable->addCell(strtoupper($sesschool['guidanceteacher']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
          }
      }else{
      
  //         $sessionsschoolist [] = $this->getSession('schoolvalues');
           $objschoolTable =& $this->newObject('htmltable', 'htmlelements');        
            $objschoolTable->cellspacing = '2';
            $objschoolTable->cellpadding = '2';
            $objschoolTable->border='0';
            $objschoolTable->width = '70%';
            
          foreach($sessionsschoolist as $sesschool){
            
                        
            $objschoolTable->startRow();
            $objschoolTable->addCell('School Name');
            $objschoolTable->addCell(strtoupper($sesschool['schoolname']));//, '', '', '', $oddOrEven);
            //$objschoolTable->addCell("<div align=\"right\">" .$editSchoollink->show() . "</div>");
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('School Address');
            $objschoolTable->addCell(strtoupper($sesschool['schooladdress']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Telephone Number');
            $objschoolTable->addCell(strtoupper($sesschool['telnumber']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Fax Number');
            $objschoolTable->addCell(strtoupper($sesschool['faxnumber']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Email');
            $objschoolTable->addCell(strtoupper($sesschool['email']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Principal');
            $objschoolTable->addCell(strtoupper($sesschool['principal']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Grade 12 guidance teacher');
            $objschoolTable->addCell(strtoupper($sesschool['guidanceteacher']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
          }
      
      
      
      }
/*----------------------------------------------------------------------------------------*/

    /**
     *create tabbed boxes to place elements in
     */
    
    $strelements  =   $objstudcardTable->show(); 
    $strelements1 =   $objactivityTable->show();
    $strelements2 =   $objschoolTable->show();
                  
    $objstudtab = new tabbedbox();
    $objstudtab->addTabLabel('Student Card Information');
    $objstudtab->addBoxContent($strelements);

    
    $objslutab = new tabbedbox();
    $objslutab->addTabLabel('SLU Activities');
    $objslutab->addBoxContent($strelements1);

    
    $objschooltab = new tabbedbox();
    $objschooltab->addTabLabel('School list');
    $objschooltab->addBoxContent($strelements2);

    $Studcardinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $Studcardinfo->tabName = 'OutputInfo';
   // $Studcardinfo->size = 60;

/*----------------------------------------------------------------------------------------*/   
   /**
   *create a tabpane to place all form out elements in
   */     
  
   $stringval = $objstudtab->show() . $objslutab->show() . $objschooltab->show();
    
//    $Studcardinfo->addTab('studcard', 'View Output', $stringval);
//    $Studcardinfo->addTab('info', 'Submit Information', $this->objSubmitstudcard->show());;
    //$Studcardinfo->addTab('schoollist', 'School List', $objschooltab->show());
  

/*  $objElement->addTab(array('name'=>'Student Cards','content' => $strelements),'luna-tab-style-sheet');
    $objElement->addTab(array('name'=>'SLU Activities','content' => $strelements1),'luna-tab-style-sheet');
    $objElement->addTab(array('name'=>'School list','content' => $strelements2),'luna-tab-style-sheet');*/
  
    /**
      *create a form to place all elements in
      */
      echo  $this->objMainheading->show();
      
      if($hasSubmitted == 'yes'){    
          $objForm = new form('editdata',$this->uri(array('action'=>'submitinfo')));
          $objForm->displayType = 3;
          $objForm->addToForm($stringval . '<br />' . $this->objSubmitstudcard->show());
              
        
      }else{
         $objForm = new form('editdata',$this->uri(array('action'=>'submitinfo','submitmsg' => 'yes')));
          $objForm->displayType = 3;
          $objForm->addToForm($stringval . '<br />' . "<div align=left>".$this->objSubmitstudcard->show()."</div>"); 
      
      }

/**************************************************************************************************/ /*PROBLEM*/                  
 // $submitmsg = " ";
if($submitmsg == 'yes'){
            $tomsg =& $this->newObject('timeoutmessage', 'htmlelements');
            $tomsg->setMessage('thank you information has been sucessfully submitted');
            echo $tomsg->show();
  
}/*else{
            $tomsg =& $this->newObject('timeoutmessage', 'htmlelements');
            $tomsg->setMessage('there is no information to submit');
            echo $tomsg->show();
}*/

/*----------------------------------------------------------------------------------------*/  
echo  $objForm->show();
 
?>
