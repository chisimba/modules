<?php
/**template to display all school information captured**/
  
/**
  *load all classes and create all objects
  */
  $this->loadClass('button','htmlelements');  
  $this->loadClass('tabbedbox', 'htmlelements');
  $this->loadClass('link', 'htmlelements');

     
  
/**
  *create all form language items
  */           
  $submit = $this->objLanguage->languageText('word_submit');
  $str1 = ucfirst($submit);
  /**
   *create all form language items
   */           
    $submit = $this->objLanguage->languageText('word_submit');
    $str1 = ucfirst($submit);
    
    $editstud = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editstud', 'marketingrecruitmentforum');
    $editslu = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editslu', 'marketingrecruitmentforum');
    $editschool = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editsschool1', 'marketingrecruitmentforum');
    $id = $this->objLanguage->languageText('mod_marketingrecruitmentforum_noidnumber','marketingrecruitmentforum');
    
    $idnumber1 = $this->objLanguage->languageText('phrase_idnumber');
    $date = $this->objLanguage->languageText('phrase_date');
    $schoolname1 = $this->objLanguage->languageText('phrase_schoolname');
    $schoolname = ucfirst($schoolname1);
    $surname1  =  $this->objLanguage->languageText('word_surname');
    $surname  = ucfirst($surname1);
    $name1 = $this->objLanguage->languageText('word_name');
    $name = ucfirst($name1);
    $postaladdress1  = $this->objLanguage->languageText('phrase_postaladdress');
    $postaladdress = ucfirst($postaladdress1);
    $postalcode1 = $this->objLanguage->languageText('phrase_postalcode');
    $postalcode = ucfirst($postalcode1);
    $telnumber1  = $this->objLanguage->languageText('phrase_telnumber');
    $telnumber = ucfirst($telnumber1);
    $telcode1  = $this->objLanguage->languageText('phrase_telephonecode');
    $telcode = ucfirst($telcode1);
    $exemption1  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_qualifyexemp','marketingrecruitmentforum');
    $exemption = ucfirst($exemption1); 
    $facultyname  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultyname','marketingrecruitmentforum');
    $crseinterest = $this->objLanguage->languageText('mod_marketingrecruitmentforum_crseinterest','marketingrecruitmentforum'); 
    $relsubj1  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_relevantsubject','marketingrecruitmentforum');
    $relsubj = ucfirst($relsubj1); 
    $sdcase = $this->objLanguage->languageText('mod_marketingrecruitmentforum_sdcase','marketingrecruitmentforum');  
    $activity = $this->objLanguage->languageText('word_activity');
    $area = $this->objLanguage->languageText('word_area');
    $province = $this->objLanguage->languageText('word_province');
    $schooladdy = $this->objLanguage->languageText('phrase_schooladdress');
    $faxno  = $this->objLanguage->languageText('phrase_faxnumber');
    $email  = $this->objLanguage->languageText('phrase_email');
    $principal  = $this->objLanguage->languageText('phrase_principal');
    $guidteacher = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteacher','marketingrecruitmentforum');
    $studcardinfo = $this->objLanguage->languageText('mod_marketingrecruitmentforum_studcardinfo','marketingrecruitmentforum');
    $sluactivity = $this->objLanguage->languageText('mod_marketingrecruitmentforum_sluactivity','marketingrecruitmentforum');
    $id1  = strtoupper($id);
  
    
  $editschool = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editsschool1', 'marketingrecruitmentforum');
  $editSchoollink = new link($this->uri(array('action' => 'editschool', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
  $editSchoollink->link = $editschool;

/**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_output','marketingrecruitmentforum');
  
/**
  *create all form buttons
  */
  $this->objSubmitstudcard  = new button('submitstudcard', $str1);
  $this->objSubmitstudcard->setToSubmit();
/**---------------------------------------------------------------------------------------**/
      $namevalue = $this->getSession('nameschool');
        $school = $this->dbschoollist->getschoolbyname($namevalue, $field = 'schoolname', $start = 0, $limit = 0);
        $this->setSession('schoovals',$school);

      
       /**
       *display all school list info contained in session
       */     
     
   if(!empty($school)){
        //Create table to display school list details in session  
            $objschoolTable =& $this->newObject('htmltable', 'htmlelements');        
            $objschoolTable->cellspacing = '2';
            $objschoolTable->cellpadding = '2';
            $objschoolTable->border='0';
            $objschoolTable->width = '70%';
          
        //  foreach($school as $sesschool){
         for($i=0; $i< count($school); $i++){
          
                        
            $objschoolTable->startRow();
            $objschoolTable->addCell($schoolname);
            $objschoolTable->addCell(strtoupper($school[$i]->SCHOOLNAME));
            
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($schooladdy);
            $objschoolTable->addCell(strtoupper($school[$i]->SCHOOLADDRESS));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Area');
            $objschoolTable->addCell(strtoupper($school[$i]->SCHOOLAREA));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Province');
            $objschoolTable->addCell(strtoupper($school[$i]->SCHOOLPROV));
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($telnumber);
            $objschoolTable->addCell(strtoupper($school[$i]->TELNUMBER));
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($faxno);
            $objschoolTable->addCell(strtoupper($school[$i]->FAXNUMBER));
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($email);
            $objschoolTable->addCell(strtoupper($school[$i]->EMAIL));
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($principal);
            $objschoolTable->addCell(strtoupper($school[$i]->PRINCIPAL));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Principal Email Address');
            $objschoolTable->addCell(strtoupper($school[$i]->PRINCIPALEMAIL));
            $objschoolTable->endRow(); 
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Principal Cell Phone Number');
            $objschoolTable->addCell(strtoupper($school[$i]->PRINCIPALCELLNO));
            $objschoolTable->endRow(); 

            
            $objschoolTable->startRow();
            $objschoolTable->addCell($guidteacher);
            $objschoolTable->addCell(strtoupper($school[$i]->GUIDANCETEACHER));
            $objschoolTable->endRow();
            
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Guidance Teacher Email');
            $objschoolTable->addCell(strtoupper($school[$i]->GUIDANCETEACHEMAIL));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Guidance Teacher Cellphone Number ');
            $objschoolTable->addCell(strtoupper($school[$i]->GUIDANCETEACHCELLNO));
            $objschoolTable->endRow();
          }
      }else{
      
      
            $sessionsschoolist [] = $this->getSession('schoolvalues');
        //Create table to display school list details in session  
            $objschoolTable =& $this->newObject('htmltable', 'htmlelements');
            $objschoolTable->cellspacing = '2';
            $objschoolTable->cellpadding = '2';
            $objschoolTable->border='0';
            $objschoolTable->width = '80%';
          
          foreach($sessionsschoolist as $sesschool){
          
                        
            $objschoolTable->startRow();
            $objschoolTable->addCell('School Name');
            $objschoolTable->addCell(strtoupper($sesschool['schoolname']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('School Address');
            $objschoolTable->addCell(strtoupper($sesschool['schooladdress']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Area / Town');
            $objschoolTable->addCell(strtoupper($sesschool['area']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();

            $objschoolTable->startRow();
            $objschoolTable->addCell('Province');
            $objschoolTable->addCell(strtoupper($sesschool['province']));//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
    
            $objschoolTable->startRow();
            $objschoolTable->addCell('Telephone Number');
            $objschoolTable->addCell($sesschool['telcode'].$sesschool['telnumber']);//, '', '', '', $oddOrEven);
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell('Fax Number');
            $objschoolTable->addCell($sesschool['faxcode'].$sesschool['faxnumber']);//, '', '', '', $oddOrEven);
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
            $objschoolTable->addCell('Principal Email Address');
            $objschoolTable->addCell((strtoupper($sesschool['principalemail'])));
            $objschoolTable->endRow(); 
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Principal Cell Phone Number');
            $objschoolTable->addCell((strtoupper($sesschool['principalCellno'])));
            $objschoolTable->endRow(); 
            
            $objschoolTable->startRow();
            $objschoolTable->addCell(ucfirst('Grade 12 guidance teacher'));
            $objschoolTable->addCell((strtoupper($sesschool['guidanceteacher'])));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Guidance Teacher Email');
            $objschoolTable->addCell((strtoupper($sesschool['guidanceteachemail'])));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Guidance Teacher Cellphone Number ');
            $objschoolTable->addCell((strtoupper($sesschool['guidanceteachcellno'])));
            $objschoolTable->endRow();
            

          }
     }

/**
  *create tabbed boxes to place elements in
  */
  $strelements2 =   $objschoolTable->show();
  $objschooltab = new tabbedbox();
  $objschooltab->addTabLabel('School Details');
  $objschooltab->addBoxContent("<div align=\"right\">" .$editSchoollink->show() . "</div>".'<br />' . $strelements2);
  
/**
  *create a tabpane to place all form out elements in
  */     
  
   $stringval = $objschooltab->show();

  /**
    *create a form to place all elements in
    */
     
     echo  $this->objMainheading->show();
     $objForm = new form('outputdata',$this->uri(array('action'=>'submitinfo','submitmsg' => 'yes')));
     $objForm->displayType = 3;
     $objForm->addToForm($stringval . '<br />' . "<div align=left>".$this->objSubmitstudcard->show()."</div>"); 
     echo  $objForm->show();
  
?>
