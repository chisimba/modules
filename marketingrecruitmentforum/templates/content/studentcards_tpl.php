<?php

  /**
   *create a template for capturing student card information
   */
      
      
   /**
     *load all classes
     */
     
     $this->loadClass('textinput','htmlelements');
     $this->loadClass('dropdown','htmlelements');
     $this->loadClass('radio','htmlelements');  
     $this->loadClass('datepicker','htmlelements');
     $this->loadClass('form','htmlelements');
     $this->loadClass('button','htmlelements');
     
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
      $exemption  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_exemption','marketingrecruitmentforum');
      $course  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_courseinterest','marketingrecruitmentforum');
      $subject  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_relevantsubject','marketingrecruitmentforum');
      $sdcase = $this->objLanguage->languageText('mod_marketingrecruitmentforum_sdcase','marketingrecruitmentforum');
      $btnNext  = $this->objLanguage->languageText('word_next');
      $str1 = ucfirst($btnNext);

      
      /**
       *create form heading
       */
       $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
       $this->objMainheading->type=1;
       $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_studentcardinterface','marketingrecruitmentforum');

                    
      /**
       *create all textinputs
       */  
        
       $this->objtxtschoolname = $this->newObject('textinput','htmlelements');          
       $this->objtxtschoolname->name   = "txtschoolname";
       $this->objtxtschoolname->value  = "";

       $this->objtxtsurname = $this->newObject('textinput','htmlelements'); 
       $this->objtxtsurname->name   = "txtsurname";
       $this->objtxtsurname->value  = "";
       
       $this->objtxtname = $this->newObject('textinput','htmlelements'); 
       $this->objtxtname->name   = "txtname";
       $this->objtxtname->value  = "";
       
       $this->objtxtpostalcode = $this->newObject('textinput','htmlelements'); 
       $this->objtxtpostalcode->name   = "txtpostalcode";
       $this->objtxtpostalcode->value  = "";
       
       $textArea = 'postaladdress';
       $this->objPostaladdress =& $this->newobject('textArea','htmlelements');
       $this->objPostaladdress->setRows(1);
       $this->objPostaladdress->setColumns(16);
       $this->objPostaladdress->setName($textArea);
       $this->objPostaladdress->setContent("");
       
       $this->objtxttelnumber = $this->newObject('textinput','htmlelements'); 
       $this->objtxttelnumber->name  = "txttelnumber";
       $this->objtxttelnumber->value  = "";

       $this->objtxttelcode = $this->newObject('textinput','htmlelements'); 
       $this->objtxttelcode->name   = "txttelcode";
       $this->objtxttelcode->value  = "";
       
       $this->objtxtcourse = $this->newObject('textinput','htmlelements'); 
       $this->objtxtcourse->name   = "txttelnumber";
       $this->objtxtcourse->value  = " ";
       
       /**
        *create a date selection
        */
        
        $this->objdate = $this->newObject('datepicker','htmlelements');
        $datename = 'txtdate';
        $datevalue= date('Y-m-d');
        $format = 'YYYY-MM-DD';
        $this->objdate->setName($name);
        $this->objdate->setDefaultDate($datevalue);
        $this->objdate->setDateFormat($format); 
        
        /**
         *create all radio groups
         */
        $objexemption = new radio('exemptionqualification');
        $objexemption->addOption('y','Yes');
        $objexemption->addOption('n','No');
        $objexemption->setSelected('y');
        
        $objsubject = new radio('relevantsubject');
        $objsubject->addOption('y','Yes');
        $objsubject->addOption('n','No');
        $objsubject->setSelected('y');
        
        $objsdcase = new radio('sdcase');
        $objsdcase->addOption('y','Yes');
        $objsdcase->addOption('n','No');
        $objsdcase->setSelected('y');
        
        /**
         *create a submit button
         */
         
         $this->objButtonSubmit  = new button('submit', $str1);
         $this->objButtonSubmit->setToSubmit();
        
        /**
         *create a table to place all elements in
         */
         
         $myTable=$this->newObject('htmltable','htmlelements');
         $myTable->width='80%';
         $myTable->border='0';
         $myTable->cellspacing='2';
         $myTable->cellpadding='10';
           
         $myTable->startRow();
         $myTable->addCell(ucfirst($date));
         $myTable->addCell($this->objdate->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($schoolname));
         $myTable->addCell($this->objtxtsurname->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($surname));
         $myTable->addCell($this->objtxtsurname->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($name));
         $myTable->addCell($this->objtxtname->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($postaladdress));
         $myTable->addCell($this->objPostaladdress->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($postalcode));
         $myTable->addCell($this->objtxtpostalcode->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($telnumber));
         $myTable->addCell($this->objtxttelnumber->show());
         $myTable->endRow();   
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($telcode));
         $myTable->addCell($this->objtxttelcode->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($exemption));
         $myTable->addCell($objexemption->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($course));
         $myTable->addCell($this->objtxtcourse->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($subject));
         $myTable->addCell($objsubject->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($sdcase));
         $myTable->addCell($objsdcase->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($this->objButtonSubmit->show());
         $myTable->endRow();
         /**
          *create a form to place all elements in
          */
          
          $objForm = new form('studentcard',$this->uri(array('action'=>'showsluactivities')));
          $objForm->displayType = 3;
          $objForm->addToForm($this->objMainheading->show() . '<br>' .$myTable->show());
          
          /**
           *display the student card interface
           */
                                
          echo  $objForm->show();	  
                         
               
?>
