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
   
   /**
    *determine if session variables containing student card data, activities data and school data is empty or not 
    *if !empty then user has not submited information as yet
    *if empty, information submitted    
    */           
     
//    $sessionstudcard [] = $this->getSession('studentdata');
    $sessionsluactivity [] = $this->getSession('sluactivitydata');
//    $sessionsschoolist [] = $this->getSession('schoolvalues');
    
    

  /*----------------------------------------------------------------------------------------*/        
        
/*        $idnum = $this->getSession('idno');
        if(!empty($idnum)){
          $id = $idnum;
        }else{
          $id = "NO ID NUMBER";
        }
        
        
        if(!empty($sessionstudcard)){
              //Create table to display student details in session  
            $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');  
            $objstudcardTable =& $this->newObject('htmltable', 'htmlelements');
            $objstudcardTable->cellspacing = '2';
            $objstudcardTable->cellpadding = '2';
            $objstudcardTable->cellwidth = '10';
            $objstudcardTable->border='0';
            $objstudcardTable->width = '80%';
  
            //var_dump($idsearch);
  
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
                $objstudcardTable->addCell($id);
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
                $objstudcardTable->addCell('Residential Area');
                $objstudcardTable->addCell(strtoupper($sesStuddata['area']));
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
             }*/
/*----------------------------------------------------------------------------------------*/
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
   *display all school list info contained in session
   */     
 
/*        if(!empty($sessionsschoolist)){
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
/*----------------------------------------------------------------------------------------*/

    /**
     *create tabbed boxes to place elements in
     */
    
//    $strelements  =   $objstudcardTable->show(); 
    $strelements1 =   $objactivityTable->show();
//    $strelements2 =   $objschoolTable->show();
                  
/*    $objstudtab = new tabbedbox();
    $objstudtab->addTabLabel('Student Card Information');
    $objstudtab->addBoxContent("<div align=\"right\">" .$editStudLink->show() . "</div>".$strelements);*/

    
    $objslutab = new tabbedbox();
    $objslutab->addTabLabel('SLU Activities');
    $objslutab->addBoxContent("<div align=\"right\">" .$editSLUlink->show() . "</div>".'<br />' . $strelements1);

    
    /*$objschooltab = new tabbedbox();
    $objschooltab->addTabLabel('School Details');
    $objschooltab->addBoxContent("<div align=\"right\">" .$editSchoollink->show() . "</div>".'<br />' . $strelements2);

    
/*----------------------------------------------------------------------------------------*/   
   /**
   *create a tabpane to place all form out elements in
   */     
  
//   $stringval = $objstudtab->show() . $objslutab->show() . $objschooltab->show();
   $stringval = $objslutab->show();

  /**
    *create a form to place all elements in
    */
      echo  $this->objMainheading->show();

      $objForm = new form('outputdata',$this->uri(array('action'=>'submitactivitydata','submitmsg' => 'yes')));
      $objForm->displayType = 3;
      $objForm->addToForm($stringval . '<br />' . "<div align=left>".$this->objSubmitstudcard->show()."</div>"); 
      echo  $objForm->show();
 
?>
