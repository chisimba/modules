<?php
//output form after edit

/*$d = $this->getSession('studentdata');
$e = $this->getSession('studentdetails');
$r = $this->getSession('studentfaccrse');
$f  = $this->getSession('studentinfo');*/
 
  
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
  $idsearch  = $this->dbstudentcard->getstudbyid11($idnumber, 'IDNUMBER', 0, 0) ;         
  $this->setSession('idsearch',$idsearch);
  
  $sessionstud [] = $this->getSession('studentdata');
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
            
      }elseif(!empty($sessionstud)){
           
     
              $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
              $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');
              $objstudcardTable->cellspacing = '2';
              $objstudcardTable->cellpadding = '2';
              $objstudcardTable->cellwidth = '10';
              $objstudcardTable->border='0';
              $objstudcardTable->width = '80%';
    
              
    
              foreach($sessionstud as $sesStud){
       
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
                  $objstudcardTable->addCell($idnumber1."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp");
                  $objstudcardTable->addCell($id2);
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($date);
                  $objstudcardTable->addCell($sesStud['date']);
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($schoolname);
                  $objstudcardTable->addCell(strtoupper($sesStud['schoolname'])); 
                  $objstudcardTable->endRow();
    
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($surname);
                  $objstudcardTable->addCell(strtoupper($sesStud['surname']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($name);
                  $objstudcardTable->addCell(strtoupper($sesStud['name']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($postaladdress);
                  $objstudcardTable->addCell(strtoupper($sesStud['postaddress']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($postalcode);
                  $objstudcardTable->addCell(strtoupper($sesStud['postcode']));
                  $objstudcardTable->endRow();
                  
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($telnumber);
                  $objstudcardTable->addCell(strtoupper($sesStud['telnumber']));
                  $objstudcardTable->endRow();
    
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell($telcode);
                  $objstudcardTable->addCell(strtoupper($sesStud['telcode']));
                  $objstudcardTable->endRow();
                  
                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Cellphone Number');
                  $objstudcardTable->addCell(strtoupper($sesStud['cellnumber']));
                  $objstudcardTable->endRow();

                  $objstudcardTable->startRow();
                  $objstudcardTable->addCell('Email Address');
                  $objstudcardTable->addCell(strtoupper($sesStud['studemail']));
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
            
            foreach($sessionstuddetails as $sesdetails){
            
                    $objTable =& $this->newObject('htmltable', 'htmlelements');  
                    $objTable->cellspacing = '2';
                    $objTable->cellpadding = '2';
                    $objTable->cellwidth = '10';
                    $objTable->border='0';
                    $objTable->width = '76%';       
                    
                    $objTable->startRow();
                    $objTable->addCell('Subject 1'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp");
                    $objTable->addCell(strtoupper($sesdetails['subject1']));
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    $objTable->addCell('Subject 2');
                    $objTable->addCell(strtoupper($sesdetails['subject2']));
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    $objTable->addCell('Subject 3');
                    $objTable->addCell(strtoupper($sesdetails['subject3']));
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    $objTable->addCell('Subject 4');
                    $objTable->addCell(strtoupper($sesdetails['subject4']));
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    $objTable->addCell('Subject 5');
                    $objTable->addCell(strtoupper($sesdetails['subject5']));
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    $objTable->addCell('Subject 6');
                    $objTable->addCell(strtoupper($sesdetails['subject6']));
                    $objTable->endRow();
                    
                    $objTable->startRow();
                    $objTable->addCell('Subject 7');
                    $objTable->addCell(strtoupper($sesdetails['subject7']));
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
                    $objTableFC->width = '80%'; 
                   
                    $objTableFC->startRow();
                    $objTableFC->addCell('Faculty 1st choice');
                    $objTableFC->addCell(strtoupper($sesfaccrse['1stfaculty']));
                    $objTableFC->endRow();
                
             //   echo '<pre>';  var_dump($sesfaccrse['1stfaculty']);
                    $objTableFC->startRow();
                    $objTableFC->addCell('Course 1st choice');
                    $objTableFC->addCell(strtoupper($sesfaccrse['1stcourse']));
                    $objTableFC->endRow();
                    
                    $objTableFC->startRow();
                    $objTableFC->addCell('Faculty 2nd choice');
                    $objTableFC->addCell(strtoupper($sesfaccrse['2ndfaculty']));
                    $objTableFC->endRow();
                    
                    $objTableFC->startRow();
                    $objTableFC->addCell('Course 2nd choice');
                    $objTableFC->addCell(strtoupper($sesfaccrse['2ndcourse']));
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
                    $objTableinfo->width = '41%';
                    
                    $objTableinfo->startRow();
                    $objTableinfo->addCell('Department information');
                    $objTableinfo->addCell(strtoupper($sesinfo['info']));
                    $objTableinfo->endRow();

                    $objTableinfo->startRow();
                    $objTableinfo->addCell('Residence');
                    $objTableinfo->addCell($resval);
                    $objTableinfo->endRow();
                    
                    $objTableinfo->startRow();
                    $objTableinfo->addCell('Exemption');
                    $objTableinfo->addCell($val);
                    $objTableinfo->endRow();
                    
                    $objTableinfo->startRow();
                    $objTableinfo->addCell('Senate Discretionary');
                    $objTableinfo->addCell($result);
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

   $objForm = new form('studentoutput',$this->uri(array('action'=>'submitinfo')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show().'<br />' .'<br />'.$stringval . '<br />' . "<div align=left>".$this->objSubmitstudcard->show()."</div>"); 
/*----------------------------------------------------------------------------------------*/  
echo $objForm->show();
?>
