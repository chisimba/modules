<?php

/**template to display all information captured**/
  
  /**
   *load all classes and create all objects
   */
    $this->loadClass('button','htmlelements');  
    $objElement =& $this->newObject('tabpane', 'htmlelements'); 
    
  /*----------------------------------------------------------------------------------------*/     
  
  /**
   *create all form language items
   */           
    $submit = $this->objLanguage->languageText('word_submit');
    $edit = $this->objLanguage->languageText('word_edit');
    
    $str1 = ucfirst($submit);
    $str2 = ucfirst($edit);
    
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
  
  $this->objSubmitactivity  = new button('submitactivity', $str1);
  $this->objSubmitactivity->setToSubmit();
  
  $this->objSubmitschools  = new button('submitschool', $str1);
  $this->objSubmitschools->setToSubmit();
  
  
  $this->objEditstudcard  = new button('editstudcard', $str2);
  $this->objEditstudcard->setToSubmit();
  
  $this->objEditactivity  = new button('edit', $str2);
  $this->objEditactivity->setToSubmit();
  
  $this->objEditschools  = new button('edit', $str2);
  $this->objEditschools->setToSubmit();
  /*----------------------------------------------------------------------------------------*/
        
  /**
   *display all student card info contained in the session variable
   */      
  $sessionstudcard [] = $this->getSession('studentdata');
      if(!empty($sessionstudcard)){
      //Create table to display student details in session  
            $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');
            $objstudcardTable->cellspacing = '2';
            $objstudcardTable->cellpadding = '2';
            $objstudcardTable->border='0';
            $objstudcardTable->width = '100%';
  
            $rowcount = '0';
  
            foreach($sessionstudcard as $sesStuddata){
     
                $oddOrEven = ($rowcount == 11) ? "odd" : "even";
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Date');
                // $objstudcardTable->addCell($sesStuddata['date'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
 
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Shool Name' );
                //$objstudcardTable->addCell($sesStuddata['schoolname'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Surname');
                //$objstudcardTable->addCell($sesStuddata['surname'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Name');
                //$objstudcardTable->addCell($sesStuddata['name'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Post Address');
                //$objstudcardTable->addCell($sesStuddata['postaddress'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Post Code');
                //$objstudcardTable->addCell($sesStuddata['postcode'], '', '', '', $oddOrEven);
                 $objstudcardTable->endHeaderRow();
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Tel Number');
                //$objstudcardTable->addCell($sesStuddata['telnumber'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Tel Code');
                //$objstudcardTable->addCell($sesStuddata['telcode'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('Exemption');
                //$objstudcardTable->addCell($sesStuddata['exemption'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('relevantsubject');
                // $objstudcardTable->addCell($sesStuddata['relevantsubject'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
  
                $objstudcardTable->startHeaderRow();
                $objstudcardTable->addHeaderCell('SD Case');
                // $objstudcardTable->addCell($sesStuddata['sdcase'], '', '', '', $oddOrEven);
                $objstudcardTable->endHeaderRow();
          }
/*  $rowcount = '0';
  
  foreach($sessionstudcard as $sesStuddata){
     
  $oddOrEven = ($rowcount == 11) ? "odd" : "even";
     
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['date'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['schoolname'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['surname'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['name'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['postaddress'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['postcode'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['telnumber'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['telcode'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['exemption'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['relevantsubject'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  $objstudcardTable->startRow();
  $objstudcardTable->addCell($sesStuddata['sdcase'], '', '', '', $oddOrEven);
  $objstudcardTable->endRow();
  
  }*/
  }
/*----------------------------------------------------------------------------------------*/
  /**
   *display all sluactivity info contained session variable
   */     
    $sessionsluactivity [] = $this->getSession('sluactivitydata');
        if(!empty($sessionsluactivity)){
        //Create table to display student details in session  
              $objactivityTable =& $this->newObject('htmltable', 'htmlelements');
              $objactivityTable->cellspacing = '2';
              $objactivityTable->cellpadding = '2';
              $objactivityTable->border='0';
              $objactivityTable->width = '100%';
  
              $objactivityTable->startHeaderRow();
              $objactivityTable->addHeaderCell('Date');
              $objactivityTable->endHeaderRow();
  
              $objactivityTable->startHeaderRow();
              $objactivityTable->addHeaderCell('Activity');
              $objactivityTable->endHeaderRow();
  
              $objactivityTable->startHeaderRow();
              $objactivityTable->addHeaderCell('School Name');
              $objactivityTable->endHeaderRow();
  
              $objactivityTable->startHeaderRow();
              $objactivityTable->addHeaderCell('Area');
              $objactivityTable->endHeaderRow();
  
              $objactivityTable->startHeaderRow();
              $objactivityTable->addHeaderCell('Province');
              $objactivityTable->endHeaderRow();
  
              /** show the captured data**/  
        }
/*----------------------------------------------------------------------------------------*/  
  /**
   *display all school list info contained in session
   */     
    $sessionsschoolist [] = $this->getSession('schoolistdata');
        if(!empty($sessionsschoolist)){
        //Create table to display student details in session  
            $objschoolTable =& $this->newObject('htmltable', 'htmlelements');
            $objschoolTable->cellspacing = '2';
            $objschoolTable->cellpadding = '2';
            $objschoolTable->border='0';
            $objschoolTable->width = '100%';
  
            $objschoolTable->startHeaderRow();
            $objschoolTable->addHeaderCell('Date');
            $objschoolTable->endHeaderRow();
  
            $objschoolTable->startHeaderRow();
            $objschoolTable->addHeaderCell('School Address');
            $objschoolTable->endHeaderRow();
  
            $objschoolTable->startHeaderRow();
            $objschoolTable->addHeaderCell('Telephone Number');
            $objschoolTable->endHeaderRow();
  
            $objschoolTable->startHeaderRow();
            $objschoolTable->addHeaderCell('Fax Number');
            $objschoolTable->endHeaderRow();
  
            $objschoolTable->startHeaderRow();
            $objschoolTable->addHeaderCell('Email');
            $objschoolTable->endHeaderRow();
  
            $objschoolTable->startHeaderRow();
            $objschoolTable->addHeaderCell('Principal');
            $objschoolTable->endHeaderRow();
  
            $objschoolTable->startHeaderRow();
            $objschoolTable->addHeaderCell('Grade 12 guidance teacher');
            $objschoolTable->endHeaderRow();
      }
/*----------------------------------------------------------------------------------------*/

  /**
   *create a tabpane to place all form out elements in
   */     
   
    $strelements  =   $objstudcardTable->show() . '<br />' . $this->objSubmitstudcard->show() . ' '.$this->objEditstudcard->show(); 
    $strelements1 =   $objactivityTable->show() . '<br />' . $this->objSubmitactivity->show() . ' '.$this->objEditactivity->show();
    $strelements2 =   $objactivityTable->show() . '<br />' . $this->objSubmitschools->show() . ' '.$this->objEditschools->show();
    
    $objElement->addTab(array('name'=>'Student Cards','content' => $strelements));
    $objElement->addTab(array('name'=>'SLU Activities','content' => $strelements1));
    $objElement->addTab(array('name'=>'School list','content' => $strelements2));
  
    echo  $this->objMainheading->show();
    echo  $objElement->show();
  
  //if(!empty($sessionstudcard)){
    //echo "<div align=\"left\">" . $objstudcardTable->show() . "</div>";
  //} 
?>
