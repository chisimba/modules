<?php
/**
 *create a template for all school lists
 */ 


/**
 *load all classes
 */
 $this->loadClass('textinput','htmlelements');
 $this->loadClass('textarea','htmlelements');
 $this->loadclass('button','htmlelements');
 
 $schoolname = $this->objLanguage->languageText('phrase_schoolname');
 $schooladdress  = $this->objLanguage->languageText('phrase_schooladdress');
 $telnumber  = $this->objLanguage->languageText('phrase_telnumber');
 $faxnumber = $this->objLanguage->languageText('phrase_faxnumber');
 $email = $this->objLanguage->languageText('word_email');
 $principal = $this->objLanguage->languageText('word_principal');
 $guidanceteacher = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteacher','marketingrecruitmentforum');
 $btnNext  = $this->objLanguage->languageText('word_next');
 $str1 = ucfirst($btnNext); 
 
  /**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_schoollist','marketingrecruitmentforum');
 
 /**
   *create all textinputs
   */  
        
   $this->objtxtschoolname = $this->newObject('textinput','htmlelements');          
   $this->objtxtschoolname->name   = "txtschoolname";
   $this->objtxtschoolname->value  = "";
  
   $textArea = 'schooladdress';
   $this->objSchooladdress =& $this->newobject('textArea','htmlelements');
   $this->objSchooladdress->setRows(1);
   $this->objSchooladdress->setColumns(16);
   $this->objSchooladdress->setName($textArea);
   $this->objSchooladdress->setContent("");
   
   $this->objtxttelnumber = $this->newObject('textinput','htmlelements'); 
   $this->objtxttelnumber->name  = "txttelnumber";
   $this->objtxttelnumber->value  = "";
   
   $this->objtxtfaxnumber = $this->newObject('textinput','htmlelements'); 
   $this->objtxtfaxnumber->name  = "txtfaxnumber";
   $this->objtxtfaxnumber->value  = "";
   
   $this->objtxtemail = $this->newObject('textinput','htmlelements'); 
   $this->objtxtemail->name  = "txtemail";
   $this->objtxtemail->value  = "";
   
   $this->objtxtprincipal = $this->newObject('textinput','htmlelements'); 
   $this->objtxtprincipal->name  = "txtprincipal";
   $this->objtxtprincipal->value  = "";
   
   $this->objtxtteacher = $this->newObject('textinput','htmlelements'); 
   $this->objtxtteacher->name  = "txtteacher";
   $this->objtxtteacher->value  = "";
   
   /**
     *create a submit button
     */
    $this->objButtonSubmit  = new button('submit', $str1);
    $this->objButtonSubmit->setToSubmit();

   
  /**
   *create a table to place all form elements in
   */
   
    $myTable=$this->newObject('htmltable','htmlelements');
    $myTable->width='80%';
    $myTable->border='0';
    $myTable->cellspacing='2';
    $myTable->cellpadding='10';
           
    $myTable->startRow();
    $myTable->addCell(ucfirst($schoolname));
    $myTable->addCell($this->objtxtschoolname->show());
    $myTable->endRow();   
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($schooladdress));
    $myTable->addCell($this->objSchooladdress->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($telnumber));
    $myTable->addCell($this->objtxttelnumber->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($faxnumber));
    $myTable->addCell($this->objtxtfaxnumber->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($email));
    $myTable->addCell( $this->objtxtemail->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($principal));
    $myTable->addCell($this->objtxtprincipal->show());
    $myTable->endRow();  
    
    $myTable->startRow();
    $myTable->addCell(ucfirst($guidanceteacher));
    $myTable->addCell($this->objtxtteacher->show());
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell($this->objButtonSubmit->show());
    $myTable->endRow();    
    
  /**
   *create a form to place all elements in
   */
   
   $objForm = new form('studentcard',$this->uri(array('action'=>'showoutput')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show() . '<br />' .$myTable->show());
          
   /**
     *display the schoolist interface
     */
                                
   echo  $objForm->show();	          
  
?>
