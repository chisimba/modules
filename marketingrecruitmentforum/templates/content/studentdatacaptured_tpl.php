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

      if(!empty($idsearch) ){
      
            $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
            $objstudcardTable->cellspacing = '2';
            $objstudcardTable->cellpadding = '2';
            $objstudcardTable->cellwidth = '10';
            $objstudcardTable->border='0';
            $objstudcardTable->width = '70%';
  
               for($i=0; $i< count($idsearch); $i++){
                $rowcount = 0; 
                 
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($idnumber1,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell($idsearch[$i]->IDNUMBER,"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($date,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell($idsearch[$i]->ENTRYDATE,"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($schoolname,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->SCHOOLNAME),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++; 
                $objstudcardTable->endRow();
  
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($surname,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->SURNAME),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($name,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->NAME),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($postaladdress,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->POSTADDRESS),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                            
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($postalcode,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->POSTCODE),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell("Residential Area","15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell($idsearch[$i]->AREA,"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($telnumber,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->TELNUMBER),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell($telcode,"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->TELCODE),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell('Cell phone number',"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->CELLNUMBER),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                $objstudcardTable->addCell('Student Email Address',"15%", null, "left",$oddOrEven);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->STUDEMAIL),"15%", null, "left",$oddOrEven);
                $myTable->row_attributes = " class = \"$oddOrEven\"";
                $rowcount++;
                $objstudcardTable->endRow();
            }
      }else{
            $sessionstudcard [] = $this->getSession('studentdata');
     
              $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
              $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');
              $objstudcardTable->cellspacing = '2';
              $objstudcardTable->cellpadding = '2';
              $objstudcardTable->cellwidth = '10';
              $objstudcardTable->border='0';
              $objstudcardTable->width = '70%';
    
              
    
              foreach($sessionstudcard as $sesStuddata){
              $rowcount = 0;
       /*           if($sesStuddata['exemption'] == 1){
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
                  }*/    
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($idnumber1."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell($sesStuddata['idnumber'],"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($date,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell($sesStuddata['date'],"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($schoolname,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['schoolname']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++; 
                  $objstudcardTable->endRow();
    
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($surname,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['surname']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($name,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['name']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($postaladdress,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['postaddress']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($postalcode,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['postcode']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell("Residential Area","15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['area']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($telnumber,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['telnumber']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell($telcode,"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['telcode']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell('Cellphone Number',"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['cellnumber']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();

                  $objstudcardTable->startRow();
                  (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                  $objstudcardTable->addCell('Email Address',"15%", null, "left",$oddOrEven);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['studemail']),"15%", null, "left",$oddOrEven);
                  $myTable->row_attributes = " class = \"$oddOrEven\"";
                  $rowcount++;
                  $objstudcardTable->endRow();    
/*                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($exemption);
//                  $objstudcardTable->addCell(strtoupper($exemptionval));
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($facultyname);
//                  $objstudcardTable->addCell(strtoupper($sesStuddata['faculty']));
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($crseinterest);
//                  $objstudcardTable->addCell(strtoupper($sesStuddata['course']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($relsubj);
//                  $objstudcardTable->addCell(strtoupper($relsubjval));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($sdcase);
//                  $objstudcardTable->addCell(strtoupper($sdvalues));
                  $objstudcardTable->endRow();*/
            }
      }
      
/*----------------------------------------------------------------------------------------*/
            $sessionstuddetails [] = $this->getSession('studentdetails');
            //var_dump($sessionstuddetails);
            
            foreach($sessionstuddetails as $sesdetails){
            
                    $objTable =& $this->newObject('htmltable', 'htmlelements');  
                    $objTable->cellspacing = '2';
                    $objTable->cellpadding = '2';
                    $objTable->cellwidth = '10';
                    $objTable->border='0';
                    $objTable->width = '70%';       
                    
                    $objTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTable->addCell('Subject 1'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp","15%", null, "left",$oddOrEven);
                    $objTable->addCell(strtoupper($sesdetails['subject1']).' '.strtoupper($sesdetails['gradetype1']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTable->addCell('Subject 2',"15%", null, "left",$oddOrEven);
                    $objTable->addCell(strtoupper($sesdetails['subject2']).' '.strtoupper($sesdetails['gradetype2']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTable->addCell('Subject 3',"15%", null, "left",$oddOrEven);
                    $objTable->addCell(strtoupper($sesdetails['subject3']).' '.strtoupper($sesdetails['gradetype3']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTable->addCell('Subject 4',"15%", null, "left",$oddOrEven);
                    $objTable->addCell(strtoupper($sesdetails['subject4']).' '.strtoupper($sesdetails['gradetype4']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTable->addCell('Subject 5',"15%", null, "left",$oddOrEven);
                    $objTable->addCell(strtoupper($sesdetails['subject5']).' '.strtoupper($sesdetails['gradetype5']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTable->addCell('Subject 6',"15%", null, "left",$oddOrEven);
                    $objTable->addCell(strtoupper($sesdetails['subject6']).' '.strtoupper($sesdetails['gradetype6']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTable->addCell('Subject 7',"15%", null, "left",$oddOrEven);
                    $objTable->addCell(strtoupper($sesdetails['subject7']).' '. strtoupper($sesdetails['gradetype7']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTable->endRow();
          }
/*----------------------------------------------------------------------------------------*/
              $sessionfaccres [] = $this->getSession('studentfaccrse');
             
             
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
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                
             //   echo '<pre>';  var_dump($sesfaccrse['1stfaculty']);
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Course 1st choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($sesfaccrse['1stcourse']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                    
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Faculty 2nd choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($sesfaccrse['2ndfaculty']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                    
                    $objTableFC->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableFC->addCell('Course 2nd choice',"15%", null, "left",$oddOrEven);
                    $objTableFC->addCell(strtoupper($sesfaccrse['2ndcourse']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableFC->endRow();
                
                }
                    
                    
/*----------------------------------------------------------------------------------------*/
               $extrainfo [] = $this->getSession('studentinfo');
               //var_dump($extrainfo);
               
               foreach($extrainfo as $sesinfo){
               $exemp = strtoupper($sesinfo['exemption']);
               $sdval = strtoupper($sesinfo['sdcase']);
               $res   = $this->getParam('residence');
           
           
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
            
                    $objTableinfo =& $this->newObject('htmltable', 'htmlelements');  
                    $objTableinfo->cellspacing = '2';
                    $objTableinfo->cellpadding = '2';
                    $objTableinfo->cellwidth = '10';
                    $objTableinfo->border='0';
                    $objTableinfo->width = '70%';
                    
                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Department information',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell(strtoupper($sesinfo['info']),"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();

                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Residence',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($resval,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();
                    
                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Exemption',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($val,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();
                    
                    $objTableinfo->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $objTableinfo->addCell('Senate Discretionary',"15%", null, "left",$oddOrEven);
                    $objTableinfo->addCell($result,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $objTableinfo->endRow();
                    
              }                    
/*----------------------------------------------------------------------------------------*/

    /**
     *create tabbed boxes to place elements in
     */
    
    $strelements  =   $objstudcardTable->show();// . $objTable->show(); 
    
    $objstudtab = new tabbedbox();
    $objstudtab->addTabLabel($studcardinfo);
    $objstudtab->addBoxContent("<div align=\"right\">" .$editStudLink->show() . "</div>"."<div align=\"left\">" .$strelements ."</div>");
    
    $objstuddetails = new tabbedbox();
    $objstuddetails->addTabLabel('Subjects');
    $objstuddetails->addBoxContent("<div align=\"right\">" .$editSubjects->show() . "</div>"."<div align=\"left\">".$objTable->show()."</div>");
    
    $objstudfaccrse = new tabbedbox();
    $objstudfaccrse->addTabLabel('Faculty and Course');
    $objstudfaccrse->addBoxContent("<div align=\"right\">" .$editFacultyCrse->show() . "</div>"."<div align=\"left\">".$objTableFC->show()."</div>");
    
    $objstudinfo = new tabbedbox();
    $objstudinfo->addTabLabel('More information');
    $objstudinfo->addBoxContent("<div align=\"right\">" .$editMoreInfo->show() . "</div>"."<div align=\"left\">".$objTableinfo->show()."</div>");
    
    /*----------------------------------------------------------------------------------------*/   
   /**
   *create a tabpane to place all form out elements in
   */     
  
   $stringval = $objstudtab->show() . $objstuddetails->show().$objstudfaccrse->show() . $objstudinfo->show();// . $objslutab->show() . $objschooltab->show();

   $objForm = new form('studentoutput',$this->uri(array('action'=>'submitinfo','submitmsg' => 'yes')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show().'<br />' .'<br />'.$stringval . '<br />' . "<div align=left>".$this->objSubmitstudcard->show()."</div>"); 
/*----------------------------------------------------------------------------------------*/  
echo $objForm->show();
?>
