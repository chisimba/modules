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
      $this->objformval = & $this->getObject('form','htmlelements');
      
//   $this->objFaculties =& $this->getObject('dbacademicprogrammefaculties','academicprogramme');
//		$this->objCourses =& $this->getObject('dbacademicprogrammecourses','academicprogramme');         
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
      
       $this->objheading =& $this->newObject('htmlheading','htmlelements');
       $this->objheading->type=5;
       $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_warning','marketingrecruitmentforum');         
/*------------------------------------------------------------------------------*/       

      /**
       *create all textinputs
       */  
       
       //call session that contains the data for of student card information
       //therefore displays data entered when user clicks edit before submitting
       $sessionstudcard = $this->getSession('studentdata');
       
        $studcarddate = '';
        $studschoolname  = '';
        $studsurname = '';
        $studname = '';
        $studpostaladdress = '';
        $studpostalcode = '';
        $studtelnumber = '';
        $studtelcode = '';
        $studcourse  = '';
        
        
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
//	$faculty = $this->objFaculties->getRow('code',$course['faculty_code']);
  $objForm1 = new form('faculty_form',$this->uri(array('action'=>'selectfaculty')));      //place on seperate form
	$objDropdown = new dropdown('faculty');                                                //create dropdown list
	$objDropdown->extra = 'onchange="document.forms[\'faculty_form\'].submit();"';         //set each value in list as onchange
//	$objDropdown->addFromDB($this->objFaculties->getAll(), 'name', 'code', $faculty);    //get value from db....populate dropdown method of dropclass
	if (!is_null($this->getSession('faculty',NULL))) {
		$objDropdown->setSelected($this->getSession('faculty',NULL));                        //set initial value ?? 
	}
	//$objButton = new button("submit_button", 'Go');
	//$objButton->setToSubmit();
	$objForm1->addToForm('<tr><td>Please Select a Faculty</td><td>'."&nbsp"."&nbsp".$objDropdown->show().'&nbsp;'/*.$objButton->show()*/.'</td></tr>');
//	echo $objForm->show();
//}
//
if (!is_null($this->getSession('faculty',NULL))) {
	$facultyCode = $this->getSession('faculty',NULL);
	$objForm = new form('course_form',$this->uri(array('action'=>'selectcourse')));
	$objDropdown = new dropdown('course');
	$objDropdown->extra = 'onchange="document.forms[\'course_form\'].submit();"';
//	$objDropdown->addFromDB($this->objCourses->getAll("WHERE faculty_code='$facultyCode'"), 'name', 'code', $course);
	if (!is_null($this->getSession('course',NULL))) {
		$objDropdown->setSelected($this->getSession('course',NULL));
	}
//	$objButton = new button("submit_button", 'Go');
//	$objButton->setToSubmit();
	$objForm->addToForm('<tr><td>Course</td><td>'."&nbsp"."&nbsp".$objDropdown->show().'&nbsp;'/*.$objButton->show()*/.'</td></tr>');
//	echo $objForm->show();
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
        $datename = 'datestud';
        $datevalue= date('Y-m-d');
        $format = 'YYYY-MM-DD';
        $this->objdate->setName($datename);
        $this->objdate->setDefaultDate($datevalue);
        $this->objdate->setDateFormat($format); 

/*------------------------------------------------------------------------------*/
        
        /**
         *create all radio groups
         */
         
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
         $strerror = 'Postal code cannot be longer than 4 digits';
//         $sucessfull  = 'not empty';
      
/*      $onClick = 'var postaddress = document.studentcard.postaladdress;
					        var postcode = document.studentcard.txtpostalcode;
					 
					 
					 
					    var acceptance = true;
					   //value of the postal address
  					 var value_postaddy = postaddress.value;
	   				 //value of the post code
		  			 var value_pcode = postcode.value;
					 
					 
					 //checks if dates are right
					 if(value_pcode > 4){
					 document.studentcard.txtpostalcode.select();
					 	acceptance = false;
						
					 }
					 
					 
							 
					 //check final condition
					 if(!acceptance){
					 	alert(\''.$strerror .'\');
						acceptance = true;
						return false;
					 }else{
       //    alert(\''.$sucessfull.'\')
           }';
	
      
      
      $this->objButtonNext->extra = sprintf('onClick ="javascript: %s"', $onClick );*/
      //$mix = 'txtpostalcode' . 4;
      //$this->objformval->_valMaxLength($mix, $strerror)  ;
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
         $myTable->cellspacing='6';
         $myTable->cellpadding='10';
           
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
         $myTable->addCell(ucfirst($postaladdress));
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objPostaladdress->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($postalcode));
         $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.' '.$this->objtxtpostalcode->show())  ;
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($telnumber));
         $myTable->addCell("&nbsp"."&nbsp".$this->objtxttelnumber->show());
         $myTable->endRow();   
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($telcode));
         $myTable->addCell("&nbsp"."&nbsp".$this->objtxttelcode->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($exemption));
         $myTable->addCell("&nbsp"."&nbsp".$objexemption->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell("&nbsp"."&nbsp". $objForm1->show());
         /*$myTable->addCell("&nbsp"."&nbsp".$facultylist->show());*/
        // $myTable->addCell($this->objButtonCourse->show());
         $myTable->endRow();


         $myTable->startRow();
         $myTable->addCell($objForm->show());
         //$myTable->addCell("&nbsp"."&nbsp".$this->objtxtcourse->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($subject));
         $myTable->addCell("&nbsp"."&nbsp".$objsubject->show());
         $myTable->endRow();
         
         $myTable->startRow();
         $myTable->addCell(ucfirst($sdcase));
         $myTable->addCell("&nbsp"."&nbsp".$objsdcase->show());
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
          $objForm->addToForm($this->objMainheading->show() . '<br />'."<span class=error>".'<i>'.$this->objheading->show().'</i>'."</span>".'<br />' .$myTable->show());
          //$objForm->addRule('schoollist','Please select a school from the list','required');
          $objForm->addRule('txtsurname','Please enter surname','required');
          $objForm->addRule('txtname','Please enter name','required');
          $objForm->addRule('postaladdress','Please enter address','required');
          //$objForm->addRule('txtpostalcode','Please enter a four digit postal code','required'); //fix up
          $objForm->addRule(array('name'=>'txtpostalcode','minnumber'=>4), 'Please enter a  4 digit postal code', 'minnumber');
          $objForm->addRule(array('name'=>'txtpostalcode','length'=>4), 'Your postal code is too long', 'maxlength');
          $objForm->addRule(array('name'=>'txttelcode','minnumber'=>3), 'Please enter a  3 digit telephone code', 'minnumber');
          $objForm->addRule(array('name'=>'txttelcode','length'=>3), 'Your telephone code is too long', 'maxlength');
/*------------------------------------------------------------------------------*/
          
          /**
           *display the student card interface
           */
                                
          echo  $objForm->show();	  
                         
               
?>
