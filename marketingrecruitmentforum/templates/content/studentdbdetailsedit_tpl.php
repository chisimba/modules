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
    
     $this->dbstudentcard  = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
     $firstname  = $this->getSession('name');
     $lastname = $this->getSession('surname');
     $idsearch = $this->getSession('idno');
     
    $idsearch  = $this->dbstudentcard->getstudbyid($idsearch, $field = 'IDNUMBER', $firstname, $field2 = 'NAME', $lastname, $field3 = 'SURNAME', $start = 0, $limit = 0);
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
  
  $objElement = new checkbox('a',NULL,true);
  $check = $objElement->show();
  
/*----------------------------------------------------------------------------------------*/

      if(!empty($idsearch)){
     
              $myTable =& $this->newObject('htmltable', 'htmlelements');
              $myTable->cellspacing = '2';
              $myTable->cellpadding = '2';
              $myTable->border='0';
              $myTable->width = '70%';
              $myTable->css_class = 'highlightrows';

             for($i=0; $i< count($idsearch); $i++){
                          $rowcount = 0; 
                          $dob  = date("d-M-Y", strtotime($idsearch[$i]->DOB));
                          
                            $myTable->startHeaderRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $myTable->addHeaderCell('Student Card Details of ', null,'top','left','header');
                            $myTable->addHeaderCell('<h3>'.$idsearch[$i]->NAME.' '.$idsearch[$i]->SURNAME.'<h3>', null,'top','left','header');
                            $myTable->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $myTable->endHeaderRow();
                  
                            $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("ID Number","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->IDNUMBER,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                        
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Date","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->ENTRYDATE,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                      
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Surname","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->SURNAME,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                        
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Name","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->NAME,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;                           
                           $myTable->endRow(); 
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Date of birth","15%", null, "left",$oddOrEven);
                           $myTable->addCell($dob,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;                           
                           $myTable->endRow(); 
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Grade","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->GRADE,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;                           
                           $myTable->endRow(); 
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("School Name","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->SCHOOLNAME,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Postal Address","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->POSTADDRESS,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Postal Code","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->POSTCODE,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Residential Area","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->AREA,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Telephone Number","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->TELNUMBER,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                            
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell("Telephone Code","15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->TELCODE,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
                           
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell('Cellphone Number',"15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->CELLNUMBER,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();
        
                           $myTable->startRow();
                           (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                           $myTable->addCell('Email Address',"15%", null, "left",$oddOrEven);
                           $myTable->addCell($idsearch[$i]->STUDEMAIL,"15%", null, "left",$oddOrEven);
                           $myTable->row_attributes = " class = \"$oddOrEven\"";
                           $rowcount++;
                           $myTable->endRow();    
            }
      }
      
/*----------------------------------------------------------------------------------------*/
         //   $sessionstuddetails [] = $this->getSession('studentdetails');
if(!empty($idsearch)){
         
                  $objTable =& $this->newObject('htmltable', 'htmlelements');  
                  $objTable->cellspacing = '2';
                  $objTable->cellpadding = '2';
                  $objTable->cellwidth = '10';
                  $objTable->border='0';
                  $objTable->width = '70%';
              for($i=0; $i< count($idsearch); $i++){
                          $rowcount = 0;
                            $objTable->startRow();
                             (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTable->addCell('Subjects and results for grade',"15%", null, "left",$oddOrEven);
                            $objTable->addCell($idsearch[$i]->MARKGRADE,"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $objTable->endRow();
                            
                            $objTable->startRow();
                             (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTable->addCell('Subject 1',"15%", null, "left",$oddOrEven);
                            $objTable->addCell($idsearch[$i]->SUBJECT1.' '.$idsearch[$i]->GRADETYPE1.' '.$idsearch[$i]->MARK1 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $objTable->endRow();
                            
                            $objTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTable->addCell('Subject 2',"15%", null, "left",$oddOrEven);
                            $objTable->addCell($idsearch[$i]->SUBJECT2.' '.$idsearch[$i]->GRADETYPE2.' '.$idsearch[$i]->MARK2 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $objTable->endRow();
                            
                            $objTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTable->addCell('Subject 3',"15%", null, "left",$oddOrEven);
                            $objTable->addCell($idsearch[$i]->SUBJECT3.' '.$idsearch[$i]->GRADETYPE3.' '.$idsearch[$i]->MARK3 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $objTable->endRow();
                            
                            $objTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTable->addCell('Subject 4',"15%", null, "left",$oddOrEven);
                            $objTable->addCell($idsearch[$i]->SUBJECT4.' '.$idsearch[$i]->GRADETYPE4.' '.$idsearch[$i]->MARK4 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $objTable->endRow();
                            
                            $objTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTable->addCell('Subject 5',"15%", null, "left",$oddOrEven);
                            $objTable->addCell($idsearch[$i]->SUBJECT5.' '.$idsearch[$i]->GRADETYPE5.' '.$idsearch[$i]->MARK5 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $objTable->endRow();
                            
                            $objTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTable->addCell('Subject 6',"15%", null, "left",$oddOrEven);
                            $objTable->addCell($idsearch[$i]->SUBJECT6.' '.$idsearch[$i]->GRADETYPE6.' '.$idsearch[$i]->MARK6 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $objTable->endRow();
                            
                            $objTable->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTable->addCell('Subject 7',"15%", null, "left",$oddOrEven);
                            $objTable->addCell($idsearch[$i]->SUBJECT7.' '.$idsearch[$i]->GRADETYPE7.' '.$idsearch[$i]->MARK7 .'%',"15%", null, "left",$oddOrEven);
                            $rowcount++;
                            $objTable->endRow();
          }
}
/*----------------------------------------------------------------------------------------*/
//              $sessionfaccres [] = $this->getSession('studentfaccrse');
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
                            $objTableFC->addCell("Faculty 1st choice","15%", null, "left",$oddOrEven);
                            $objTableFC->addCell($idsearch[$i]->FACULTY,"15%", null, "left",$oddOrEven);
                            $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $objTableFC->endRow();
                          
                            $objTableFC->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTableFC->addCell("Course 1st choice","15%", null, "left",$oddOrEven);
                            $objTableFC->addCell($idsearch[$i]->COURSE,"15%", null, "left",$oddOrEven);
                            $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $objTableFC->endRow();
                            
                            $objTableFC->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTableFC->addCell('Faculty 2nd choice',"15%", null, "left",$oddOrEven);
                            $objTableFC->addCell($idsearch[$i]->FACULTY2,"15%", null, "left",$oddOrEven);
                            $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $objTableFC->endRow();
                            
                            $objTableFC->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTableFC->addCell('Course 2nd choice',"15%", null, "left",$oddOrEven);
                            $objTableFC->addCell($idsearch[$i]->COURSE2,"15%", null, "left",$oddOrEven);
                            $objTableFC->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $objTableFC->endRow();
                
                }
                
}
                    
                    
/*----------------------------------------------------------------------------------------*/
if(!empty($idsearch)){        
             
             
              for($i=0; $i< count($idsearch); $i++){
                          $rowcount = 0;
                          if($idsearch[$i]->EXEMPTION == 1){
                                $exemptionval = 'YES';
                          }else{
                                $exemptionval = 'NO';
                          }
                
                          if($idsearch[$i]->RESIDENCE== 1){
                                $residence = 'YES';
                            }else{
                                $residence = 'NO';
                          }
                        
                          if($idsearch[$i]->SDCASE == 1){
                              $sdvalues = 'YES';
                          }else{
                              $sdvalues = 'NO';
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
                            $objTableinfo->addCell($idsearch[$i]->INFODEPARTMENT,"15%", null, "left",$oddOrEven);
                            $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $objTableinfo->endRow();
        
                            $objTableinfo->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTableinfo->addCell('Residence',"15%", null, "left",$oddOrEven);
                            $objTableinfo->addCell($residence,"15%", null, "left",$oddOrEven);
                            $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $objTableinfo->endRow();
                            
                           /* $objTableinfo->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTableinfo->addCell('Exemption',"15%", null, "left",$oddOrEven);
                            $objTableinfo->addCell($exemptionval,"15%", null, "left",$oddOrEven);
                            $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $objTableinfo->endRow();
                            
                            $objTableinfo->startRow();
                            (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                            $objTableinfo->addCell('Senate Discretionary',"15%", null, "left",$oddOrEven);
                            $objTableinfo->addCell($sdvalues,"15%", null, "left",$oddOrEven);
                            $objTableinfo->row_attributes = " class = \"$oddOrEven\"";
                            $rowcount++;
                            $objTableinfo->endRow();*/
                    
              }                    
}
/*----------------------------------------------------------------------------------------*/

    /**
     *create tabbed boxes to place elements in
     */
    
    $strelements  =   $myTable->show();// . $objTable->show(); 
    
    $objstudtab = new tabbedbox();
    $objstudtab->addTabLabel('Student Personal Data');
    $objstudtab->addBoxContent("<div align=\"right\">" .$editStudLink->show() . "</div>"."<div align=\"left\">" .$strelements ."</div>");
    
    $objstuddetails = new tabbedbox();
    $objstuddetails->addTabLabel('Subjects taken at school');
    $objstuddetails->addBoxContent("<div align=\"right\">" .$editSubjects->show() . "</div>"."<div align=\"left\">".$objTable->show()."</div>");
    
    $objstudfaccrse = new tabbedbox();
    $objstudfaccrse->addTabLabel('Faculties / Courses');
    $objstudfaccrse->addBoxContent("<div align=\"right\">" .$editFacultyCrse->show() . "</div>"."<div align=\"left\">".$objTableFC->show()."</div>");
    
    $objstudinfo = new tabbedbox();
    $objstudinfo->addTabLabel('Additional information');
    $objstudinfo->addBoxContent("<div align=\"right\">" .$editMoreInfo->show() . "</div>"."<div align=\"left\">".$objTableinfo->show()."</div>");
    
    /*----------------------------------------------------------------------------------------*/   
   /**
   *create a tabpane to place all form out elements in
   */     
  
   $stringval = $objstudtab->show() . $objstuddetails->show().$objstudfaccrse->show() . $objstudinfo->show();// . $objslutab->show() . $objschooltab->show();

   $objForm = new form('studentoutput',$this->uri(array('action'=>'submitinfo')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show().'<br />' .'<br />'.$stringval . '<br />'  .'<h3>'. " I hereby confirm that the information entered above is complete and accurate.".$check.'</h>'.' '. ' '."<div align=left>".$this->objSubmitstudcard->show()."</div>");
   $objForm->addRule('a','Field not checked','required');  
/*----------------------------------------------------------------------------------------*/  
echo $objForm->show();
?>
