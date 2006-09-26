<?php
/**
 *create a template for all school lists
 */ 

/**
 *load all classes
 */
 $this->loadClass('textinput','htmlelements');
 $this->loadClass('textarea','htmlelements');
 
 $schoolname = $this->objLanguage->languageText('phrase_schoolname');
 $schooladdress  = $this->objLanguage->languageText('phrase_schooladdress');
 $telnumber  = $this->objLanguage->languageText('phrase_telnumber');
 $faxnumber = $this->objLanguage->languageText('phrase_faxnumber');
 $email = $this->objLanguage->languageText('word_email');
 $principal = $this->objLanguage->languageText('word_principal');
 $guidanceteacher = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteacher','marketingrecruitmentforum'); 
 
 /**
   *create all textinputs
   */  
        
   $this->objtxtschoolname = $this->newObject('textinput','htmlelements');          
   $this->objtxtschoolname->name   = "txtschoolname";
   $this->objtxtschoolname->value  = "";
   
   $this->objtxtaddress = $this->newObject('textinput','htmlelements');          
   $this->objtxtaddress->name   = "txtschoolname";
   $this->objtxtaddress->value  = "";
   
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
   $this->objtxtprincipal->name  = "txtemail";
   $this->objtxtprincipal->value  = "";
   
   $this->objtxtteacher = $this->newObject('textinput','htmlelements'); 
   $this->objtxtteacher->name  = "txtteacher";
   $this->objtxtteacher->value  = "";
   
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
    $myTable->addCell($this->objtxtaddress->show());
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
    
  /**
   *create a form to place all elements in
   */
   
   $objForm = new form('studentcard',$this->uri(array('action'=>'null')));
   $objForm->displayType = 3;
   $objForm->addToForm($myTable->show());
          
   /**
     *display the schoolist interface
     */
                                
   echo  $objForm->show();	          
   
   
   
   
   
   
   
   
   
   
 
  
?>
