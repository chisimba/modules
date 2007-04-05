<?php
//template for searching for student by id no, determine if student exist within db
       
     /**
       *load classes
       */               
       $this->loadClass('button','htmlelements');
       $this->loadClass('textinput','htmlelements');
       $this->loadClass('domtt','htmlelements');
       
/*----------------------------------------------------------------------------*/       
      /**
       *create form heading
       */
       $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
       $this->objMainheading->type=1;
       $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_studinterface1','marketingrecruitmentforum');
       
       $this->objheading =& $this->newObject('htmlheading','htmlelements');
       $this->objheading->type=5;
       $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_warning','marketingrecruitmentforum');         

/*----------------------------------------------------------------------------*/
      /**
       *define language item elements
       */
       $idnumber  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_idnumber1','marketingrecruitmentforum');
       $str1  = $this->objLanguage->languageText('word_search');
       $requiredid  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_requiredidno','marketingrecruitmentforum');     
       $idminwarning = $this->objLanguage->languageText('mod_marketingrecruitmentforum_idminwarning1','marketingrecruitmentforum');
       $idexceed  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_idexceed','marketingrecruitmentforum');         
/*----------------------------------------------------------------------------*/
      /**
       *create search button
       */
       $this->objsearch  = new button('search', $str1);
       $this->objsearch->setToSubmit();             
       
       $this->objcontinue  = new button('continue', 'No ID Number Available');
       $this->objcontinue->setToSubmit();
/*----------------------------------------------------------------------------*/
      /**
       *create textbox
       */
       $this->objtxtidnumber = new textinput('idnumber'); 
       $this->objtxtidnumber->value  = "No id number";
       
     
       
       $this->objtxtfirstname = new textinput('firstname'); 
       $this->objtxtfirstname->value  = "";             
       
       $this->objtxtlastname = new textinput('lastname'); 
       $this->objtxtlastname->value  = "";            
       
            
/*----------------------------------------------------------------------------*/
      /**
       *create table to place elements in
       */
       $oddEven = 'even';
       $myTable =& $this->newObject('htmltable', 'htmlelements');
       $myTable->cellspacing = '1';
       $myTable->cellpadding = '2';
       $myTable->border='0';
       $myTable->width = '60%';
       
       $myTable->startRow();
       $myTable->addCell($idnumber);
       $myTable->addCell("&nbsp".$this->objtxtidnumber->show());
       $myTable->startRow();
       
       $myTable->startRow();
       $myTable->addCell('Please enter surname');
       $myTable->addCell("<span class=error>" .'*'."</span>".$this->objtxtfirstname->show());
       $myTable->startRow();
       
       $myTable->startRow();
       $myTable->addCell('Please enter first name');
       $myTable->addCell("<span class=error>" .'*'."</span>".$this->objtxtlastname->show());
       $myTable->startRow();
                    
       $myTable->startRow();
       $myTable->addCell($this->objsearch->show());
       $myTable->startRow();
                     
/*----------------------------------------------------------------------------*/
      /**
       *create form objects to place all elements on
       */
       $objForm = new form('idsearch',$this->uri(array('action'=>'searchidnumber')));
       $objForm->displayType = 3;
       $objForm->addToForm($this->objMainheading->show() .'<br />'."<span class=error>".'<i>'.$this->objheading->show().'</i>'."</span>". '<br />'. $myTable->show());
       $objForm->addRule('firstname','Please enter surname','required');
       $objForm->addRule(array('name'=>'firstname','length'=>45),'Name cannot be longer than 45characters', 'maxlength');
       $objForm->addRule('lastname','Please enter name','required');
       $objForm->addRule(array('name'=>'lastname','length'=>45),'Surname cannot be longer than 45characters', 'maxlength');
       //$objForm->addRule(array('name'=>'idnumber','length'=>13),$idexceed, 'maxlength');
       
       

/*----------------------------------------------------------------------------*/
      //display info to the screan
      echo $objForm->show();       
?>
