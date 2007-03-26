<?php
//student data captured output

//$d = $this->getSession('studentdata');
//$e = $this->getSession('studentdetails');
//$r = $this->getSession('studentfaccrse');
//$f  = $this->getSession('studentinfo');
 
  
  /**
   *load all classes and create all objects
   */
    $this->loadClass('button','htmlelements');  
    $this->loadClass('tabbedbox', 'htmlelements');
    $this->loadClass('link', 'htmlelements');
    $this->loadClass('checkbox', 'htmlelements');

/*----------------------------------------------------------------------------------------*/     
  
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
    
/*----------------------------------------------------------------------------------------*/    
  /**
   *create all link elements
   */     

    $editStudLink = new link($this->uri(array('action' => 'editstudcard', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editStudLink->link = 'Edit';
    
    $editSubjects = new link($this->uri(array('action' => 'editsubjects', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editSubjects->link = 'Edit';
    
    $editSport = new link($this->uri(array('action' => 'editsport', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editSport->link = 'Edit';
    
    $editFacultyCrse = new link($this->uri(array('action' => 'editfacultycrse', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editFacultyCrse->link = 'Edit';
    
    $editMoreInfo = new link($this->uri(array('action' => 'editextra', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editMoreInfo->link = 'Edit';
/*----------------------------------------------------------------------------------------*/ 

 /**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_changes12','marketingrecruitmentforum');
/*----------------------------------------------------------------------------------------*/ 
  
  /**
   *create all form buttons
   */
  $this->objSubmitstudcard  = new button('submitstudcard', $str1);
  $this->objSubmitstudcard->setToSubmit();

  $objElement15 = new checkbox('confirmation',NULL,true);
  $check15 = $objElement15->show();
/*----------------------------------------------------------------------------------------*/

   /**
    *Table created to display the output/data captured of student card interface   
    *Get session with student idno
    *Call to function getstudbyid($idnum) using idno in session
    *create a table based on this information        
    */
    $idnumber = $this->getSession('idno');
    if(!empty($idnumber)){
      $id2  = $idnumber;
    }else{
      $id2 = $id1;
    }
    $firstname  = $this->getSession('name');
    $lastname = $this->getSession('surname');
    $this->dbstudentcard  = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
    $idsearch  = $this->dbstudentcard->getstudbyid($idnumber, $field = 'IDNUMBER', $firstname, $field2 = 'NAME', $lastname, $field3 = 'SURNAME', $start = 0, $limit = 0);
   
    $editedID = $this->getSession('changeIDnumber');
    if(!empty($editedID)){
        $studentIDNum = $editedID;
    }else{
        $studentIDNum = $id2;
    }  
    $sessionstudcard [] = $this->getSession('studentdata');
    if(!empty($sessionstudcard) && ($sessionstudcard [0] != NULL)){
           foreach($sessionstudcard as $sesStuddata){ 
                $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
                $objstudcardTable->cellspacing = '2';
                $objstudcardTable->cellpadding = '2';
                $objstudcardTable->cellwidth = '10';
                $objstudcardTable->border='0';
                $objstudcardTable->width = '70%';
    
          
                  $rowcount = 0;
       
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell('ID Number'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell($studentIDNum,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($date,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell($sesStuddata['date'],"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($schoolname,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['schoolname']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++; 
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($surname,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['surname']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($name,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['name']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell('Date of birth',"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell($sesStuddata['dob'],"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell('Grade',"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['grade']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($postaladdress,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['postaddress']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($postalcode,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['postcode']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell("Residential Area","15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['area']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($telnumber,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['telnumber']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($telcode,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['telcode']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell('Cellphone Number',"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['cellnumber']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();

                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell('Email Address',"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['studemail']),"15%", null, "left",$oddOrEven);
                  $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
              }
    }else{
          if(!empty($idsearch)){
                  for($i=0; $i< count($idsearch); $i++){
                $rowcount = 0;
                $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
                $objstudcardTable->cellspacing = '2';
                $objstudcardTable->cellpadding = '2';
                $objstudcardTable->cellwidth = '10';
                $objstudcardTable->border='0';
                $objstudcardTable->width = '70%';
  
                
                 
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($idnumber1,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell($idsearch[$i]->IDNUMBER,"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($date,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell($idsearch[$i]->ENTRYDATE,"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($schoolname,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->SCHOOLNAME),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++; 
                $objstudcardTable->endRow();
  
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($surname,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->SURNAME),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($name,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->NAME),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($postaladdress,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->POSTADDRESS),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                            
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($postalcode,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->POSTCODE),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell("Residential Area","15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell($idsearch[$i]->AREA,"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($telnumber,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->TELNUMBER),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($telcode,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->TELCODE),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell('Cell phone number',"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->CELLNUMBER),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell('Student Email Address',"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->STUDEMAIL),"15%", null, "left",$oddOrEven);
                $objstudcardTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
            }
          }
        }
                
/*----------------------------------------------------------------------------------------*/
            $sessionstuddetails [] = $this->getSession('studentdetails');
            if(!empty($sessionstuddetails)&& ($sessionstuddetails [0] != NULL)){
            
                foreach($sessionstuddetails as $sesdetails){
                        $rowcount = 0;    
                        $objTable =& $this->newObject('htmltable', 'htmlelements');  
                        $objTable->cellspacing = '2';
                        $objTable->cellpadding = '2';
                        $objTable->cellwidth = '10';
                        $objTable->border='0';
                        $objTable->width = '70%';       
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 1'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($sesdetails['subject1']).' '.strtoupper($sesdetails['gradetype1']).' '.$sesdetails['mark1'].'%',"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 2',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($sesdetails['subject2']).' '.strtoupper($sesdetails['gradetype2']).' '.$sesdetails['mark2'].'%',"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 3',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($sesdetails['subject3']).' '.strtoupper($sesdetails['gradetype3']).' '.$sesdetails['mark3'].'%',"15%", null, "left",$oddOrEven);
                        $myTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 4',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($sesdetails['subject4']).' '.strtoupper($sesdetails['gradetype4']).' '.$sesdetails['mark4'].'%',"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 5',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($sesdetails['subject5']).' '.strtoupper($sesdetails['gradetype5']).' '.$sesdetails['mark5'].'%',"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 6',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($sesdetails['subject6']).' '.strtoupper($sesdetails['gradetype6']).' '.$sesdetails['mark6'].'%',"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 7',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($sesdetails['subject7']).' '. strtoupper($sesdetails['gradetype7']).' '.$sesdetails['mark7'],"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                  }
          }else{
              if(!empty($idsearch)){
                  for($i=0; $i< count($idsearch); $i++){
                        $rowcount = 0;
                
                        $objTable =& $this->newObject('htmltable', 'htmlelements');  
                        $objTable->cellspacing = '2';
                        $objTable->cellpadding = '2';
                        $objTable->cellwidth = '10';
                        $objTable->border='0';
                        $objTable->width = '70%';       
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 1'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($idsearch[$i]->SUBJECT1).' '.strtoupper($idsearch[$i]->GRADETYPE1),"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 2',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($idsearch[$i]->SUBJECT2).' '.strtoupper($idsearch[$i]->GRADETYPE2),"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 3',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($idsearch[$i]->SUBJECT3).' '.strtoupper($idsearch[$i]->GRADETYPE3),"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 4',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($idsearch[$i]->SUBJECT4).' '.strtoupper($idsearch[$i]->GRADETYPE4),"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 5',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($idsearch[$i]->SUBJECT5).' '.strtoupper($idsearch[$i]->GRADETYPE5),"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 6',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($idsearch[$i]->SUBJECT6).' '.strtoupper($idsearch[$i]->GRADETYPE6),"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();
                        
                        $objTable->startRow();
                        (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                        $objTable->addCell('Subject 7',"15%", null, "left",$oddOrEven);
                        $objTable->addCell(strtoupper($idsearch[$i]->SUBJECT7).' '.strtoupper($idsearch[$i]->GRADETYPE7),"15%", null, "left",$oddOrEven);
                        $objTable->row_attributes = " class = \"$oddOrEven\"";
                        $rowcount++;
                        $objTable->endRow();                 
                   }
                   
              }
    }
/*----------------------------------------------------------------------------------------*/
    $sessionsportdetails [] = $this->getSession('sportdata');
    if(!empty($sessionsportdetails)&& ($sessionsportdetails [0] != NULL)){
    
        foreach($sessionsportdetails as $sesssport){
                     $val = '';
                    $sportC [] = $sesssport['sportCode'];
                    foreach($sportC[0] as $sport){
                         $val .= $sport .'<br/>';
                    }
                    
                    $value = '';
                    $achievlevel [] = $sesssport['achievlevel'];
                    foreach($achievlevel[0] as $achiev){
                         $value .= $achiev .'<br/>';
                    }
                    
                    $objTablesport =& $this->newObject('htmltable', 'htmlelements');  
                    $objTablesport->cellspacing = '2';
                    $objTablesport->cellpadding = '2';
                    $objTablesport->cellwidth = '10';
                    $objTablesport->border='0';
                    $objTablesport->width = '70%'; 
                   
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Sport Participation',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($sesssport['sportPart']),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();
                    
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Leadership position(s)',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($sesssport['leadershipPos']),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();
                    
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Sport code(s)',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($val),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();   
                                     
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Achievement level',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($value),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();
                    
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Apply for sport bursary',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($sesssport['sportBursary']),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();                             
        
        }
     }else{
            if(!empty($idsearch)){
                for($i=0; $i< count($idsearch); $i++){
                        $rowcount = 0;
                
                    $objTablesport =& $this->newObject('htmltable', 'htmlelements');  
                    $objTablesport->cellspacing = '2';
                    $objTablesport->cellpadding = '2';
                    $objTablesport->cellwidth = '10';
                    $objTablesport->border='0';
                    $objTablesport->width = '70%';       
                 
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Sport Participation',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($idsearch[$i]->SPORTPART),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();
                    
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Leadership position(s)',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($idsearch[$i]->LEADERSHIPPOS),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();
                    
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Sport code(s)',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($idsearch[$i]->SPORTCODE),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();   
                                     
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Achievement level',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($idsearch[$i]->ACHIEVELEVEL),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();
                    
                    $objTablesport->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTablesport->addCell('Apply for sport bursary',"15%", null, "left",$oddOrEven);
                    $objTablesport->addCell(strtoupper($idsearch[$i]->SPORTBURSARY),"15%", null, "left",$oddOrEven);
                    $objTablesport->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTablesport->endRow();
                  }
               }
            }            
/*----------------------------------------------------------------------------------------*/
        $sessionfaccres [] = $this->getSession('studentfaccrse');
        if(!empty($sessionfaccres)&& ($sessionfaccres [0] != NULL)){ 
            
              foreach($sessionfaccres as $sesfaccrse){
            
                    $objTableFC =& $this->newObject('htmltable', 'htmlelements');  
                    $objTableFC->cellspacing = '2';
                    $objTableFC->cellpadding = '2';
                    $objTableFC->cellwidth = '10';
                    $objTableFC->border='0';
                    $objTableFC->width = '70%'; 
                   
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Faculty 1st choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($sesfaccrse['1stfaculty']),"15%", null, "left",$oddOrEven);
                    $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                
             
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Course 1st choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($sesfaccrse['1stcourse']),"15%", null, "left",$oddOrEven);
                    $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                    
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Faculty 2nd choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($sesfaccrse['2ndfaculty']),"15%", null, "left",$oddOrEven);
                    $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                    
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Course 2nd choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($sesfaccrse['2ndcourse']),"15%", null, "left",$oddOrEven);
                    $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                 }
       }else{
              if(!empty($idsearch)){
              
                    for($i=0; $i< count($idsearch); $i++){
                    $rowcount = 0;
                    
                    $objTableFC =& $this->newObject('htmltable', 'htmlelements');  
                    $objTableFC->cellspacing = '2';
                    $objTableFC->cellpadding = '2';
                    $objTableFC->cellwidth = '10';
                    $objTableFC->border='0';
                    $objTableFC->width = '70%'; 
                   
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Faculty 1st choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($idsearch[$i]->FACULTY),"15%", null, "left",$oddOrEven);
                    $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Course 1st choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($idsearch[$i]->COURSE),"15%", null, "left",$oddOrEven);
                    $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                    
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Faculty 2nd choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($idsearch[$i]->FACULTY2),"15%", null, "left",$oddOrEven);
                    $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                    
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Course 2nd choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($idsearch[$i]->COURSE2),"15%", null, "left",$oddOrEven);
                    $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                    
                    }
            
            }
        }
                    
                    
/*----------------------------------------------------------------------------------------*/
    $extrainfo [] = $this->getSession('studentinfo');
    if(!empty($extrainfo)&& ($extrainfo [0] != NULL)){
      foreach($extrainfo as $sesinfo){
               $exemp = strtoupper($sesinfo['exemption']);
               $sdval = strtoupper($sesinfo['sdcase']);
               $res   = $this->getParam('residence');
           
               if($sdval == '1'){
                  $result = 'YES';
               }elseif($sdval == '0'){
                  $result = 'NO';
               }else{
                  $result = 'Null';
               }
               
               
               if($exemp == '1'){
                 $val = 'YES';
               }elseif($exemp == '0'){
                 $val = 'NO';
               }else{
                 $val = 'Null';
               }
                   
                if($res == '1'){
                  $resval = 'YES';
                }else{
                  $resval = 'NO';
                } 
                
                 $v = '';
                $addInfo [] = $sesinfo['info'];
                foreach($addInfo[0] as $add){
                   $v .= $add .'<br/>';
                }              
             
                    $objTableinfo =& $this->newObject('htmltable', 'htmlelements');  
                    $objTableinfo->cellspacing = '2';
                    $objTableinfo->cellpadding = '2';
                    $objTableinfo->cellwidth = '10';
                    $objTableinfo->border='0';
                    $objTableinfo->width = '70%';
                    
                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Department information',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell(strtoupper($v),"15%", null, "left",$oddOrEven);
                    $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();

                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Residence',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($resval,"15%", null, "left",$oddOrEven);
                    $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();
                    
                   /* $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Exemption',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($val,"15%", null, "left",$oddOrEven);
                    $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();
                    
                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Senate Discretionary',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($result,"15%", null, "left",$oddOrEven);
                    $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();*/
                    
              }
      }else{
                    
        if(!empty($idsearch)){
              for($i=0; $i< count($idsearch); $i++){
               $exemp = $idsearch[$i]->EXEMPTION;
               $sdval = $idsearch[$i]->SDCASE;
               $res   = $idsearch[$i]->RESIDENCE;
           
           
               if($sdval == '1'){
                  $result = 'YES';
               }else{
                  $result = 'NO';
               }
               
               
               if($exemp == '1'){
                 $val = 'YES';
               }else{
                 $val = 'NO';
               }
                   
                if($res == '1'){
                  $resval = 'YES';
                }else{
                  $resval = 'NO';
                } 
                
                    $rowcount = 0;
            
                    $objTableinfo =& $this->newObject('htmltable', 'htmlelements');  
                    $objTableinfo->cellspacing = '2';
                    $objTableinfo->cellpadding = '2';
                    $objTableinfo->cellwidth = '10';
                    $objTableinfo->border='0';
                    $objTableinfo->width = '70%';
                    
                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Department information',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell(strtoupper($idsearch[$i]->INFODEPARTMENT),"15%", null, "left",$oddOrEven);
                    $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();

                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Residence',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($resval,"15%", null, "left",$oddOrEven);
                    $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();
                    
                   /* $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Exemption',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($val,"15%", null, "left",$oddOrEven);
                    $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();
                    
                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Senate Discretionary',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($result,"15%", null, "left",$oddOrEven);
                    $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();*/            
            }   
        } 
     }                
/*----------------------------------------------------------------------------------------*/

    /**
     *create tabbed boxes to place elements in
     */
    
  //  $strelements  =   $objstudcardTable->show();// . $objTable->show(); 
    
    $objstudtab = new tabbedbox();
    $objstudtab->addTabLabel('Student Personal Data');
    $objstudtab->addBoxContent("<div align=\"right\">" .$editStudLink->show() . "</div>"."<div align=\"left\">" .$objstudcardTable->show() ."</div>");
    
    $objstuddetails = new tabbedbox();
    $objstuddetails->addTabLabel('Subjects taken at school');
    $objstuddetails->addBoxContent("<div align=\"right\">" .$editSubjects->show() . "</div>"."<div align=\"left\">".$objTable->show()."</div>");
    
    $objstudsport = new tabbedbox();
    $objstudsport->addTabLabel('Sports Information');
    $objstudsport->addBoxContent("<div align=\"right\">" .$editSport->show() . "</div>"."<div align=\"left\">".$objTablesport->show()."</div>");
    
    $objstudfaccrse = new tabbedbox();
    $objstudfaccrse->addTabLabel('Faculties / Courses');
    $objstudfaccrse->addBoxContent("<div align=\"right\">" .$editFacultyCrse->show() . "</div>"."<div align=\"left\">".$objTableFC->show()."</div>");
    
    $objstudinfo = new tabbedbox();
    $objstudinfo->addTabLabel('Addtional Information');
    $objstudinfo->addBoxContent("<div align=\"right\">" .$editMoreInfo->show() . "</div>"."<div align=\"left\">".$objTableinfo->show()."</div>");
    
    /*----------------------------------------------------------------------------------------*/   
   /**
   *create a tabpane to place all form out elements in
   */     
  
   $stringval = $objstudtab->show() . $objstuddetails->show().$objstudsport->show().$objstudfaccrse->show() . $objstudinfo->show();// . $objslutab->show() . $objschooltab->show();

   $objForm = new form('studentoutput',$this->uri(array('action'=>'submitinfo','submitmsg' => 'yes')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show().'<br />' .'<br />'.$stringval . '<br />' .'<h3>'. " I hereby confirm that the information entered above is complete and accurate.".$check15.'</h>'.' '. ' '."<div align=left>".$this->objSubmitstudcard->show()."</div>");
   $objForm->addRule('confirmation','Field not checked','required'); 
/*----------------------------------------------------------------------------------------*/  
echo $objForm->show();
?>
