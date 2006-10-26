<?php

  /**
   *create a template for capturing student card information
   */
      
/*------------------------------------------------------------------------------*/      
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
      *create object of all classes used
      */
      
      $this->objfaculty = & $this->getObject('faculty','marketingrecruitmentforum');          
/*------------------------------------------------------------------------------*/     
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

/*------------------------------------------------------------------------------*/      
      
      /**
       *create form heading
       */
       $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
       $this->objMainheading->type=1;
       $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_studentcardinterface','marketingrecruitmentforum');

/*------------------------------------------------------------------------------*/       

      /**
       *create all textinputs
       */  
       
       //call session that contains the data for of student card information
       $sessionstudcard = $this->getSession('studentdata');
       
        $studcarddate = '';
        $studschoolname  = '';
        $studsurname = '';
        $studname = '';
        $studpostaladdress = '';
        $studpostalcode = '';
        $studtelnumber = '';
        $studtelcode = '';
        //$studexemption  = '';
        $studcourse  = '';
        //$studsubject =  ''; 
        //$studsdcase = '';
        
       if(!empty($sessionstudcard)){         
              while(list($subkey,$subval) = each($sessionstudcard))
              {
                  
                  if($subkey == 'studschoolname') {
                  $studschoolname = $subval;
                  }
                  if($subkey == 'surname') {
                  $studsurname = $subval;
                  }
                  if($subkey == 'name') {
                  $studname = $subval;
                  }
                  if($subkey  ==  'postaddress'){
                  $studpostaladdress  = $subval;
                  }
                  if($subkey == 'postcode') {
                  $studpostalcode = $subval;
                  }
                  if($subkey  ==  'telnumber'){
                  $studtelnumber = $subval;
                  }
                  if($subkey == 'telcode') {
                  $studtelcode = $subval;
                  }
                  if($subkey  ==  'courseinterest') {
                  $studcourse = $subval;
                  }
                  
                  
              }
          }
        
      
/*--------------------------------------------------------------------------------------------*/               
       //create an object of the schoolnames class
       //call the function that sets the session
       //call the session
       //populate list with values in the session array 
       $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
       $this->objschoolname->readfiledata();
        
       $schoollist  = new dropdown('schoollist');
       $shoolvalues = $this->getSession('schoolnames');
       sort($shoolvalues);
       
       foreach($shoolvalues as $sessschool){
          
          $schoollist->addOption($sessschool,$sessschool);
          
       }
       
/*--------------------------------------------------------------------------------------------*/       
      
      
      /*$postcodes  = new dropdown('postcodes');
      $this->objschoolname->readpostcodes();
      $postvalues = $this->getSession('postcodevals');
      var_dump($postvalues);
      die;
      sort($postvalues);
      
      foreach($postvalues as $sesspost){
          
          $postcodes->addOption($sesspost,$sesspost);
      }*/
/*--------------------------------------------------------------------------------------------*/
       /*$this->objtxtschoolname = $this->newObject('textinput','htmlelements');    //change to dropdown populate with info in link          
       $this->objtxtschoolname->name   = "txtschoolname";
       $this->objtxtschoolname->value  = $studschoolname;*/

       $this->objtxtsurname = $this->newObject('textinput','htmlelements'); 
       $this->objtxtsurname->name   = "txtsurname";
       $this->objtxtsurname->value  = $studsurname;
       
       $this->objtxtname = $this->newObject('textinput','htmlelements'); 
       $this->objtxtname->name   = "txtname";
       $this->objtxtname->value  = $studname;
       
       $this->objtxtpostalcode = $this->newObject('textinput','htmlelements'); 
       $this->objtxtpostalcode->name   = "txtpostalcode";
       $this->objtxtpostalcode->value  = $studpostalcode;
       $this->objtxtpostalcode->size  = 10;
       
       $textArea = 'postaladdress';
       $this->objPostaladdress =& $this->newobject('textArea','htmlelements');
       $this->objPostaladdress->setRows(1);
       $this->objPostaladdress->setColumns(15);
       $this->objPostaladdress->setName($textArea);
       $this->objPostaladdress->setContent($studpostaladdress);
       
       $this->objtxttelnumber = $this->newObject('textinput','htmlelements'); 
       $this->objtxttelnumber->name  = 'txttelnumber';
       $this->objtxttelnumber->value  = $studtelnumber;

       $this->objtxttelcode = $this->newObject('textinput','htmlelements'); 
       $this->objtxttelcode->name   = "txttelcode";
       $this->objtxttelcode->value  = $studtelcode;
       $this->objtxttelcode->size = 10;
       
       //$this->objfaculty->displaycourses();
       
       $this->objtxtcourse = $this->newObject('textinput','htmlelements'); 
       $this->objtxtcourse->name   = "txtcourse";
       $this->objtxtcourse->value  = $studcourse;
/*------------------------------------------------------------------------------*/       
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

/*------------------------------------------------------------------------------*/
        
        /**
         *create all radio groups
         */
        //$selected = ($selected == 'No') ? '0' : '1';
			//$objRadio->setSelected($selected); 
        $objexemption = new radio('exemptionqualification');
        $objexemption->addOption('1','Yes');
        $objexemption->addOption('0','No');
        $objexemption->setSelected('1');
        
        $objsubject = new radio('relevantsubject');
        $objsubject->addOption('1','Yes');
        $objsubject->addOption('0','No');
        $objsubject->setSelected('1');
        
        $objsdcase = new radio('sdcase');
        $objsdcase->addOption('1','Yes');
        $objsdcase->addOption('0','No');
        $objsdcase->setSelected('1');

/*------------------------------------------------------------------------------*/        
        /**
         *create a next button
         */
         
         $this->objButtonNext  = new button('next', $str1);
         $this->objButtonNext->setToSubmit();
         
         $this->objButtonCourse  = new button('course', $str1);
         $this->objButtonCourse->setToSubmit();
/*------------------------------------------------------------------------------*/
        
        /**
         *create all dropdownlist
         */
         
         $facultylist = new dropdown('facultylist');
         $this->objfaculty->displayfaculty();         //call function in the faculty class -- sets the session
         $facultyvals = $this->getSession('faculty'); //get info from session
         //var_dump($facultyvals);
         //die;
         sort($facultyvals);                         //sort contents of the array
         
         foreach($facultyvals as $sessfac){
         
              $facultylist->addOption($sessfac,$sessfac); //populate the dropdwon list with array faculty contents
         
         
         }              
/*------------------------------------------------------------------------------*/
        
        /**
         *create a table to place all elements in
         */
         
         $myTable=$this->newObject('htmltable','htmlelements');
         $myTable->width='80%';
         $myTable->border='0';
         $myTable->cellspacing='5';
         $myTable->cellpadding='10';
           
         $myTable->startRow();
         $myTable->addCell(ucfirst($date));
         $myTable->addCell($this->objdate->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($schoolname));
         $myTable->addCell($schoollist->show());
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
         $myTable->addCell($this->objtxtpostalcode->show())  ;
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
         $myTable->addCell("Select a faculty");
         $myTable->addCell($facultylist->show());
        // $myTable->addCell($this->objButtonCourse->show());
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
         $myTable->addCell($this->objButtonNext->show());
         $myTable->endRow();

/*------------------------------------------------------------------------------*/
         
         /**
          *create a form to place all elements in
          */
          
          $objForm = new form('studentcard',$this->uri(array('action'=>'showsluactivities')));
          $objForm->displayType = 3;
          $objForm->addToForm($this->objMainheading->show() . '<br>' .$myTable->show());
          
/*------------------------------------------------------------------------------*/
          
          /**
           *display the student card interface
           */
                                
          echo  $objForm->show();	  
                         
               
?>
