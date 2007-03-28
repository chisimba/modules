<?php

  /**create a template for capturing student card information**/
      
   /**
     *load all classes
     */
     $this->loadClass('textinput','htmlelements');
     $this->loadClass('dropdown','htmlelements');
     $this->loadClass('radio','htmlelements'); 
     $this->loadClass('datepicker','htmlelements');
     $this->loadClass('form','htmlelements');
     $this->loadClass('button','htmlelements');
     $this->loadClass('textarea','htmlelements');
     
     /**
      *create objects of all classes used
      */
      
      $this->objfaculty = & $this->getObject('faculty','marketingrecruitmentforum'); 
      $this->objformval = & $this->getObject('form','htmlelements');
      $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
      $this->objstudcard =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
		         
/*------------------------------------------------------------------------------*/     
    /**
      *create all language elements
      */
      $idnumber = $this->objLanguage->languageText('phrase_idnumber');
      $date = $this->objLanguage->languageText('phrase_date');
      $schoolname = $this->objLanguage->languageText('phrase_schoolname');
      $surname  =  $this->objLanguage->languageText('word_surname');
      $name = $this->objLanguage->languageText('word_name');
      $postaladdress  = $this->objLanguage->languageText('phrase_postaladdress');
      $postalcode = $this->objLanguage->languageText('phrase_postalcode');
      $telnumber  = $this->objLanguage->languageText('phrase_telnumber');
      $telcode  = $this->objLanguage->languageText('phrase_telephonecode');
      $exemption  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_exemption1','marketingrecruitmentforum');
      $course  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_courseinterest','marketingrecruitmentforum');
      $subject  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_relevantsubject','marketingrecruitmentforum');
      $sdcase = $this->objLanguage->languageText('mod_marketingrecruitmentforum_sdcase','marketingrecruitmentforum');
      $btnNext  = $this->objLanguage->languageText('word_next');
      $str1 = ucfirst($btnNext);
      $schoolselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoolselect','marketingrecruitmentforum');
      $id1 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_noidnumber','marketingrecruitmentforum');
      $idval = strtoupper($id1);
      $areaselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_areaselect','marketingrecruitmentforum');
      $facultyselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultymsg1','marketingrecruitmentforum');
      $courseselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_coursemsg','marketingrecruitmentforum');
      
      $surnameval = $this->objLanguage->languageText('mod_marketingrecruitmentforum_surnamval','marketingrecruitmentforum');
      $surnamemaxval  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_surnammaxval','marketingrecruitmentforum');
      $nameval  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_namval','marketingrecruitmentforum');
      $namemaxval = $this->objLanguage->languageText('mod_marketingrecruitmentforum_namemaxval','marketingrecruitmentforum');
      $postaladd  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_postaladdy','marketingrecruitmentforum');
      $postcode = $this->objLanguage->languageText('mod_marketingrecruitmentforum_postcode','marketingrecruitmentforum');
      $postcodelng  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_longcode','marketingrecruitmentforum');
      $telcode  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_telcode','marketingrecruitmentforum');
      $telcodelng  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_longtelcode','marketingrecruitmentforum');
      $schoolselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoolselect','marketingrecruitmentforum');


/*------------------------------------------------------------------------------*/      
      
      /**
       *create form headings
       */
       $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
       $this->objMainheading->type=1;
       $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_editstudcardtpl2','marketingrecruitmentforum');
      
       $this->objheading =& $this->newObject('htmlheading','htmlelements');
       $this->objheading->type=5;
       $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_warning','marketingrecruitmentforum');
       
       $id = $this->objLanguage->languageText('mod_marketingrecruitmentforum_noidnumber','marketingrecruitmentforum');
       $id1 = ucfirst($id);         
/*------------------------------------------------------------------------------*/       
    /**
	    * Used to populate all textfields with default values contained in a session variable.
	    * Usefull for editing, therefore textfields will be populated with values in session	    
	    * Calls the session - studentdata and assigns it to a variable $sessionstudcard
	    * If the session is not empty loop through it and assign each value to a variable
	    */
       
       
        $sessionstudcard = $this->getSession('studentdata');
        $studcarddate = '';
        $studschoolname  = '';
        $studsurname = $this->getSession('name');
        $studname = $this->getSession('surname');
        $studpostaladdress = '';
        $studpostalcode = '';
        $studtelnumber = '';
        $studtelcode = '';
        $studcourse  = '';
        $cellphno = '';
        $emailaddy  = '';
        
        $idval = $this->getSession('idno');
        
        if(!empty($idval)){
        $id2  = $idval;
        }else{
        $id2 = $id1;
        }
        
        
        if(!empty($sessionstudcard)){         
              while(list($subkey,$subval) = each($sessionstudcard))
              {
                  
                  if($subkey == 'studschoolname') {
                     $studschoolname = $subval;
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
                  if($subkey == 'cellnumber') {
                     $studcellno = $subval;
                  }
                  if($subkey == 'studemail') {
                     $studemail = $subval;
                  }
              }
              
          }

        /**
          * Used to to populate textfields if user capture student card using an idea no         
          * Calls the session that stores id no entered
          * Uses the id session in a function used to get the students detail with matching id no	          
          * Usefull for editing, therefore textfields will be populated with values in session	    
          * Loop through each value and assign is a value
          */
	        $idnum = $this->getSession('idno');  
	        $firstname  = $this->getSession('name');
          $lastname = $this->getSession('surname');
          $idsearch  = $this->dbstudentcard->getstudbyid($idnum, $field = 'IDNUMBER', $firstname, $field2 = 'NAME', $lastname, $field3 = 'SURNAME', $start = 0, $limit = 0); 
          if(!empty($idsearch)){         
             
              for($i=0; $i< count($idsearch); $i++){
                  
                  $studsurname = $idsearch[$i]->SURNAME;
                  $studname = $idsearch[$i]->NAME;
                  $studpostaladdress = $idsearch[$i]->POSTADDRESS;
                  $studpostalcode = $idsearch[$i]->POSTCODE;
                  $studtelcode = $idsearch[$i]->TELCODE;
                  $studtelnumber = $idsearch[$i]->TELNUMBER;
                  $studcellno = $idsearch[$i]->CELLNUMBER;
                  $studemail = $idsearch[$i]->STUDEMAIL;
                  $id2 = $idsearch[$i]->IDNUMBER;
              }
          }
/*--------------------------------------------------------------------------------------------*/               
      /**
        * Used to create a dropdown list of all schoolnames
        * Call the webservice function that contains all schoolinfo
        * loop through the object $schoolnames and store the info(schoolnames) into a multi-dim array
        * create dropdwonlist and populate it with values in the multi-dim array
        */
        
       $schoolnames = $this->objfaculties->getSchools(); 
       for($i=0; $i < count($schoolnames); $i++){
            $school[$i]=$schoolnames[$i]->SCHOOLNAME;
       }
       //create dropdown list
       $schoollist  = new dropdown('schoollist');
       
       
       sort($school);
       foreach($school as $sessschool){
          $schoollist->addOption(NULL, ''.$schoolselect);
          $schoollist->addOption($sessschool,$sessschool);
       }
       
/*--------------------------------------------------------------------------------------------*/
/**
 *create a dropdown list with all area values
 */
     $postAreaInfo = $this->objfaculties->getPostInfo(); 
    //echo "<pre>";
    //print_r($postAreaInfo);die;
       for($i=0; $i < count($postAreaInfo); $i++){
            $areavals[$i]=$postAreaInfo[$i]->CITY;
       }
       //create dropdown list
       $arealist  = new dropdown('areaschool');
       
       
       sort($areavals);
       foreach($areavals as $sessarea){
          $arealist->addOption(NULL, ''.'Please select an area');
          $arealist->addOption($sessarea,$sessarea);
       }  
/*--------------------------------------------------------------------------------------------*/       
      /**
       *create all textinputs
       */ 
       
       $this->objtxtsurname = new textinput("txtsurname");
       $this->objtxtsurname->value  = $studsurname;
       $this->objtxtsurname->size  = 35;
       
       $this->objtxtidnumber = new textinput('studentidnumber'); 
       $this->objtxtidnumber->value  = $id2;
       
       $this->objtxtname = new textinput("txtname");
       $this->objtxtname->value  = $studname;
       $this->objtxtname->size  = 35;
       
       $this->objtxtpostalcode = new textinput("txtpostalcode");
       $this->objtxtpostalcode->value  = $studpostalcode;
       $this->objtxtpostalcode->size  = 6;
       
      
       $this->objPostaladdress = new textArea('postaladdress');
       $this->objPostaladdress->setRows(4);
       $this->objPostaladdress->setColumns(48);
       $this->objPostaladdress->setContent($studpostaladdress);
       
       $this->objtxttelnumber = new textinput('txttelnumber');
       $this->objtxttelnumber->value  = $studtelnumber;
       $this->objtxttelnumber->size  = 26;

       $this->objtxtcellno = new textinput("txtcellno");
       $this->objtxtcellno->value  = $studcellno;
       $this->objtxtcellno->size  = 35;
       
       $this->objtxtemailaddy = new textinput("txtemail");
       $this->objtxtemailaddy->value  = $studemail;
       $this->objtxtemailaddy->size  = 35;

       $this->objtxttelcode = new textinput("txttelcode");
       $this->objtxttelcode->value  = $studtelcode;
       $this->objtxttelcode->size = 3;
       
      $this->objgradedropdown  = new dropdown('grade');
      $this->objgradedropdown->addOption(NULL,' ');
      $this->objgradedropdown->addOption('9','9');
      $this->objgradedropdown->addOption('10','10');
      $this->objgradedropdown->addOption('11','11');                 
      $this->objgradedropdown->addOption('12','12');
/*------------------------------------------------------------------------------*/       
       /**
        *create a date selection
        */
        
        $this->objdate = $this->newObject('datepicker','htmlelements');
        $datename = 'datestud';
        $datevalue= date('d-m-Y');
        $format = 'DD-MM-YYYY';
        $this->objdate->setName($datename);
        $this->objdate->setDefaultDate($datevalue);
        $this->objdate->setDateFormat($format); 
        
        $this->objdob = $this->newObject('datepicker','htmlelements');
        $name = 'txtdob';
        $value= '01-Jan-0000';
        $format = 'DD-MM-YYYY';
        $this->objdob->setName($name);
        $this->objdob->setDefaultDate($value);
        $this->objdob->setDateFormat($format); 

        /**
         *create a next button
         */
         $this->objButtonNext  = new button('next', $str1);
         $this->objButtonNext->setToSubmit();
/*------------------------------------------------------------------------------*/
        /**
         *create a table to place all elements in
         */
         
         $myTable=$this->newObject('htmltable','htmlelements');
         $myTable->width='80%';
         $myTable->border='0';
         $myTable->cellspacing='6';
         $myTable->cellpadding='10';
         
         $idnum = $this->getSession('idno');
         if(!empty($idnum)){
            $idval = $idnum;
         }else{
            $idval = $id1;
         }
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($idnumber));
         $myTable->addCell("&nbsp"." ".$this->objtxtidnumber->show());
         $myTable->endRow();
         
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($date));
         $myTable->addCell($this->objdate->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($schoolname));
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$schoollist->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($surname));
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtsurname->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($name));
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtname->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst('Date of birth'));
         $myTable->addCell($this->objdob->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell('Grade');
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objgradedropdown->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($postaladdress));
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objPostaladdress->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($postalcode));
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtpostalcode->show())  ;
         $myTable->endRow();
         
         
         $myTable->startRow();
         $myTable->addCell('Residential Area');
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$arealist->show())  ;
         $myTable->endRow();
         
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($telnumber));
         $myTable->addCell("&nbsp"."&nbsp".$this->objtxttelcode->show().' '.$this->objtxttelnumber->show());
         $myTable->endRow();   
         
         $myTable->startRow();
         $myTable->addCell('Cellphone Number');
         $myTable->addCell("&nbsp"."&nbsp".$this->objtxtcellno->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell('Email Address');
         $myTable->addCell("&nbsp"."&nbsp".$this->objtxtemailaddy->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell($this->objButtonNext->show());
         $myTable->endRow();

/*------------------------------------------------------------------------------*/
         
         /**
          *create a form to place all elements in
          */
          $objForm = new form('studentcard',$this->uri(array('action'=>'showoutput')));
          $objForm->displayType = 3;
          $objForm->addToForm($this->objMainheading->show() . '<br />'."<span class=error>".'<i>'.$this->objheading->show().'</i>'."</span>".'<br />' .$myTable->show());
          $objForm->addRule('schoollist','Please select a school','required');
          $objForm->addRule('txtsurname',$surnameval,'required');
          $objForm->addRule(array('name'=>'txtsurname','length'=>45), $surnamemaxval, 'maxlength');
          $objForm->addRule('txtname',$nameval,'required');
          $objForm->addRule(array('name'=>'txtname','length'=>45), $namemaxval, 'maxlength');
          $objForm->addRule('grade','Please select a grade','required');
          $objForm->addRule('postaladdress',$postaladd,'required');
          $objForm->addRule(array('name'=>'txtpostalcode','minnumber'=>4), $postcode, 'minnumber');
          $objForm->addRule(array('name'=>'txtpostalcode','length'=>4), $postcodelng, 'maxlength');
          $objForm->addRule('areaschool','Please select an area','required');
          $emailinfo = $this->getParam('txtemail');
          if(!empty($emailinfo)){
            $objForm->addRule('txtemail','Please select an area','email');
          }
/*------------------------------------------------------------------------------*/
          
          /**
           *display the student card interface
           */
          echo  $objForm->show();	  
?>
