<?php

/**This template is used to edit data captured by the user**/    
  
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
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_changes12','marketingrecruitmentforum');
/*----------------------------------------------------------------------------------------*/ 
  
  /**
   *create all form buttons
   */
  $this->objSubmitstudcard  = new button('submitstudcard', $str1);
  $this->objSubmitstudcard->setToSubmit();
/*----------------------------------------------------------------------------------------*/
   
   /**
    *determine if session variables containing student card data, activities data and school data is empty or not 
    *if !empty then user has not submited information as yet, therefore variable $hasSubmitted is initialised to no
    *if empty, information submitted and therefore variable $hasSubmitted is initialised to yes 
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
  $idsearch  = $this->dbstudentcard->getstudbyid11($idnumber, 'IDNUMBER', 0, 0) ;         
  $this->setSession('idsearch',$idsearch);
      if(!empty($idsearch) ){
      
            $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
            $objstudcardTable->cellspacing = '2';
            $objstudcardTable->cellpadding = '2';
            $objstudcardTable->cellwidth = '10';
            $objstudcardTable->border='0';
            $objstudcardTable->width = '80%';
  
           // foreach($idsearch as $sesStuddata){
             for($i=0; $i< count($idsearch); $i++){
     
                if($idsearch[$i]->EXEMPTION == 1){
                    $exemptionval = 'yes';
                }else{
                      $exemptionval = 'no';
                }
                
                if($idsearch[$i]->RELEVANTSUBJECT == 1){
                    $relsubjval = 'yes';
                }else{
                    $relsubjval = 'no';
                }
                if($idsearch[$i]->SDCASE == 1){
                    $sdvalues = 'yes';
                }else{
                    $sdvalues = 'no';
                }    
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($idnumber1);
                $objstudcardTable->addCell($id2);
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($date);
                $objstudcardTable->addCell($idsearch[$i]->ENTRYDATE);
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($schoolname);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->SCHOOLNAME)); 
                $objstudcardTable->endRow();
  
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($surname);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->SURNAME));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($name);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->NAME));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($postaladdress);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->POSTADDRESS));
                $objstudcardTable->endRow();
  
                
                            
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($postalcode);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->POSTCODE));
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell("Residential Area");
                $objstudcardTable->addCell($idsearch[$i]->AREA);
                $objstudcardTable->endRow();
                
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($telnumber);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->TELNUMBER));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($telcode);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->TELCODE));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($exemption);
                $objstudcardTable->addCell(strtoupper($exemptionval));
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($facultyname);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->FACULTY));
                $objstudcardTable->endRow();
                
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($crseinterest);
                $objstudcardTable->addCell(strtoupper($idsearch[$i]->COURSE));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($relsubj);
                $objstudcardTable->addCell(strtoupper($relsubjval));
                $objstudcardTable->endRow();
  
                $objstudcardTable->startRow();
                $objstudcardTable->addCell($sdcase);
                $objstudcardTable->addCell(strtoupper($sdvalues));
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
                  $objstudcardTable->addCell($idnumber1);
                  $objstudcardTable->addCell($id2);
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($date);
                  $objstudcardTable->addCell($sesStuddata['date']);
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($schoolname);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['schoolname'])); 
                  $objstudcardTable->endRow();
    
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($surname);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['surname']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($name);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['name']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($postaladdress);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['postaddress']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($postalcode);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['postcode']));
                  $objstudcardTable->endRow();
                  
                  /*$myTable->startRow();
                    $myTable->addCell("Area");
                    $myTable->addCell(strtoupper($sesStuddata['area']));
                    $myTable->endRow();*/
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($telnumber);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['telnumber']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($telcode);
                  $objstudcardTable->addCell(strtoupper($sesStuddata['telcode']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
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
                  $objstudcardTable->endRow();
            }
      }
      
      
/*----------------------------------------------------------------------------------------*/
  /**
   *display all sluactivity info contained session variable
   */     
  //  $sessionsluactivity [] = $this->getSession('sluactivitydata');
        if(!empty($sessionsluactivity)){
       
              $objactivityTable =& $this->newObject('htmltable', 'htmlelements');
              $objactivityTable->cellspacing = '2';
              $objactivityTable->cellpadding = '2';
              $objactivityTable->border='0';
              $objactivityTable->width = '105%';
            
            foreach($sessionsluactivity as $sesSLU){
            
              $objactivityTable->startRow();
              $objactivityTable->addCell($date);
              $objactivityTable->addCell(strtoupper($sesSLU['date']));
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              $objactivityTable->addCell($activity);
              $objactivityTable->addCell(strtoupper($sesSLU['activity']));
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              $objactivityTable->addCell($schoolname);
              $objactivityTable->addCell(strtoupper($sesSLU['schoolname']));
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              $objactivityTable->addCell($area);
              $objactivityTable->addCell(strtoupper($sesSLU['area']));
              $objactivityTable->endRow();
  
              $objactivityTable->startRow();
              $objactivityTable->addCell($province);
              $objactivityTable->addCell(strtoupper($sesSLU['province']));
              $objactivityTable->endRow();
            }
             
        }
/*----------------------------------------------------------------------------------------*/  
  /**
   *display all school list info contained in session
   */     
       
        $namevalue = $this->getSession('nameschool');
        $school = $this->dbschoollist->getschoolbyname($namevalue, $field = 'schoolname', $start = 0, $limit = 0);
        $this->setSession('schoovals',$school);
        
        
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
      
 
            $objschoolTable =& $this->newObject('htmltable', 'htmlelements');        
            $objschoolTable->cellspacing = '2';
            $objschoolTable->cellpadding = '2';
            $objschoolTable->border='0';
            $objschoolTable->width = '70%';
            
          foreach($sessionsschoolist as $sesschool){
            
                        
            $objschoolTable->startRow();
            $objschoolTable->addCell($schoolname);
            $objschoolTable->addCell(strtoupper($sesschool['schoolname']));
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($schooladdy);
            $objschoolTable->addCell(strtoupper($sesschool['schooladdress']));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Area');
            $objschoolTable->addCell(strtoupper($sesschool['area']));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Province');
            $objschoolTable->addCell(strtoupper($sesschool['province']));
            $objschoolTable->endRow();
  
            
            $objschoolTable->startRow();
            $objschoolTable->addCell($telnumber);
            $objschoolTable->addCell(strtoupper($sesschool['telnumber']));
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($faxno);
            $objschoolTable->addCell(strtoupper($sesschool['faxnumber']));
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($email);
            $objschoolTable->addCell(strtoupper($sesschool['email']));
            $objschoolTable->endRow();
  
            $objschoolTable->startRow();
            $objschoolTable->addCell($principal);
            $objschoolTable->addCell(strtoupper($sesschool['principal']));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Principal Email Address');
            $objschoolTable->addCell(strtoupper($sesschool['principalemail']));
            $objschoolTable->endRow(); 
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Principal Cell Phone Number');
            $objschoolTable->addCell(strtoupper($sesschool['principalCellno']));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell($guidteacher);
            $objschoolTable->addCell(strtoupper($sesschool['guidanceteacher']));
            $objschoolTable->endRow();
            
            
                                 
            $objschoolTable->startRow();
            $objschoolTable->addCell('Guidance Teacher Email');
            $objschoolTable->addCell(strtoupper($sesschool['guidanceteachemail']));
            $objschoolTable->endRow();
            
            $objschoolTable->startRow();
            $objschoolTable->addCell('Guidance Teacher Cellphone Number ');
            $objschoolTable->addCell(strtoupper($sesschool['guidanceteachcellno']));
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
    $objstudtab->addTabLabel($studcardinfo);
    $objstudtab->addBoxContent("<div align=\"right\">" .$editStudLink->show() . "</div>".$strelements);

    
    $objslutab = new tabbedbox();
    $objslutab->addTabLabel($sluactivity);
    $objslutab->addBoxContent("<div align=\"right\">" .$editSLUlink->show() . "</div>".'<br />' . $strelements1);

    
    $objschooltab = new tabbedbox();
    $objschooltab->addTabLabel('School Details');
    $objschooltab->addBoxContent("<div align=\"right\">" .$editSchoollink->show() . "</div>".'<br />' . $strelements2);

    $Studcardinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $Studcardinfo->tabName = 'OutputInfo';
   
/*----------------------------------------------------------------------------------------*/   
   /**
   *create a tabpane to place all form out elements in
   */     
  
   $stringval = $objstudtab->show() . $objslutab->show() . $objschooltab->show();

   $objForm = new form('editdata',$this->uri(array('action'=>'submitinfo')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show().'<br />' .'<br />'.$stringval . '<br />' . "<div align=left>".$this->objSubmitstudcard->show()."</div>"); 
/*----------------------------------------------------------------------------------------*/  
echo  $objForm->show();
 
?>
