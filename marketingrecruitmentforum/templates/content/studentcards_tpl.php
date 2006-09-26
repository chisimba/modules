<?php

  /**
   *create a template for capturing student card information
   */
      
      
   /**
     *load all classes
     */
     
     $this->loadClas('textinput','htmlelements');
     $this->loadClas('htmlheadindgs','htmlelements');
     $this->loadClas('dropdown','htmlelements');
     $this->loadClas('radio','htmlelements'); 
     $this->loadClass('htmlheading','htmlelements');
     $this->loadClass('datepicke','htmlelements');
     $this->objexpensesdate = $this->newObject('datepicker','htmlelements');
     
    /**
      *create all language elements
      */
      
      $date = $this->objLanguage->languageText('word_date');
      $schoolname = $this->objLanguage->languageText('phrase_schoolname');
      $surname  =  $this->objLanguage->languageText('word_surname');
      $name = $this->objLanguage->languageText('word_name');
      $postaladdress  = $this->objLanguage->languageText('phrase_postaladdress');
      $postalcode = $this->objLanguage->languageText('phrase_postalcode');
      $telnumber  = $this->objLanguage->languageText('phrase_telnumber');
      $telcode  = $this->objLanguage->languageText('phrase_telephonecode');
      $exemption  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_exemptionqualification','marketingrecruitmentforum');
      $course  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_courseinterest','marketingrecruitmentforum');
      $subject  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_relevantsubject','marketingrecruitmentforum');
      $sdcase = $this->objLanguage->languageText('mod_marketingrecruitmentforum_sdcase','marketingrecruitmentforum');
      
      /**
       *create all textinputs
       */   
                 
       $this->objtxtschoolname->name   = "txtschoolname";
       $this->objtxtschoolname->value  = "";

       $this->objtxtsurname->name   = "txtsurname";
       $this->objtxtsurname->value  = "";
       
       
       $this->objtxtname->name   = "txtname";
       $this->objtxtname->value  = " ";
       
       $this->objtxtpostalcode->name   = "txtpostalcode";
       $this->objtxtpostalcode->value  = " ";
       
       $this->objtxtpostaladdress->name   = "txtbreakfastLocation";
       $this->objtxtpostaladdress->value  = " ";
       
       $this->objtxttelnumber->name   = "txttelnumber";
       $this->objtxttelnumber>value  = " ";

       $this->objtxttelcode->name   = "txttelcode";
       $this->objtxttelcode>value  = " ";
       
       $this->objtxtcourse->name   = "txttelnumber";
       $this->objtxtcourse->value  = " ";
       
       /**
        *create a date selection
        */
        
        $objdate = new datepicker('txtdate');
        $datevalue = date('Y-m-d');
        $format = 'YYYY-MM-DD';
        $objdate->setDefaultDate($datevalue);
        $objdate->setDateFormat($format);
        
        /**
         *create all radio groups
         */
        $objexemption = new radio('exemptionqualification');
        $objexemption->addOption('y','Yes');
        $objexemption->addOption('n','No');
        $objexemption->setSelected('y');
        
        $objsubject = new radio('relevant_subject');
        $objsubject->addOption('y','Yes');
        $objsubject->addOption('n','No');
        $objsubject->setSelected('y');
        
        $objsdcase = new radio('sd_case');
        $objsdcase->addOption('y','Yes');
        $objsdcase->addOption('n','No');
        $objsdcase->setSelected('y');
        
        /**
         *create a table to place all elements in
         */
         
         $myTable=$this->newObject('htmltable','htmlelements');
         $myTable->width='60%';
         $myTable->border='1';
         $myTable->cellspacing='1';
         $myTable->cellpadding='10';
           
         $myTable->startRow();
         $myTable->addCell($date);
         $myTable->addCell($objdate);
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($schoolname);
         $myTable->addCell($this->objtxtsurname->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($surname);
         $myTable->addCell($this->objtxtsurname->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($name);
         $myTable->addCell($this->objtxtname->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($postaladdress);
         $myTable->addCell($this->objtxtpostaladdress->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($postalcode);
         $myTable->addCell($this->objtxtpostalcode->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($telnumber);
         $myTable->addCell($this->objtxttelnumber->show());
         $myTable->endRow();   
         
         $myTable->startRow();
         $myTable->addCell($telcode);
         $myTable->addCell($this->objtxttelnumber->show());
         $myTable->endRow();
         
         /**
          *create a form to place all elements in
          */
          
          $this->loadClass('form','htmlelements');
          $objForm = new form('studentcard',$this->uri(array('action'=>'null')));
          $objForm->displayType = 3;
          $objForm->addToForm($myTable->show());
          
          /**
           *display the student card interface
           */
                                
          echo  $objForm->show();	                   
         
               
         
         
        
        
                                        
                 
?>
