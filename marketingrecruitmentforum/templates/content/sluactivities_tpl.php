<?php
/**
 *create a tempate for the SLU activities interface
 */ 

/*------------------------------------------------------------------------------*/ 
/**
  *load all classes
  */
  
  $this->loadClass('datepicker','htmlelements');
  $this->loadClass('dropdown','htmlelements');
  $this->loadClass('textinput','htmlelements');
  $this->loadClass('button','htmlelements');
  
  $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/  
  
/**
  *create all language elements
  */
  
  $date = $this->objLanguage->languageText('word_date1');
  $activity = $this->objLanguage->languageText('word_activity');
  $school = $this->objLanguage->languageText('phrase_schoolname');
  $area = $this->objLanguage->languageText('phrase_area');
  $province = $this->objLanguage->languageText('word_province');
  $btnNext  = $this->objLanguage->languageText('word_next');
  $str1 = ucfirst($btnNext);
  $schoolselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoolselect','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/  

/**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_sluactivities12','marketingrecruitmentforum');

/*------------------------------------------------------------------------------*/  
/**
  *create a dropdown list containing school values
  */   
       $schoolvalues = array();
       $schoolnames = $this->objfaculties->getSchools(); 
       for($i=0; $i < count($schoolnames); $i++){
            $schoolvalues[$i]=$schoolnames[$i]->SCHOOLNAME;
       }
       
       $searchlist  = new dropdown('schoollistactivity');
     //  $searchlist->size = 50;
       
       //populate list values with array data
       sort( $schoolvalues);
       foreach( $schoolvalues as $sessschool){
          $searchlist->addOption(NULL, ''.$schoolselect);
          $searchlist->addOption($sessschool,$sessschool);
       }
      

       
/*--------------------------------------------------------------------------------------------*/       
/**
 *create a dropdown list with all area values
 */
  
       $postAreaInfo = $this->objfaculties->getPostInfo(); 
       for($i=0; $i < count($postAreaInfo); $i++){
            $areavals[$i]=$postAreaInfo[$i]->CITY;
       }
    //   echo "<pre>";
   // print_r($areavals);die;
       //create dropdown list
       $arealist  = new dropdown('area');
      // $arealist->size = 50;
       
       sort($areavals);
       foreach($areavals as $sessarea){
          $arealist->addOption(NULL, ''.'Please select an area');
          $arealist->addOption($sessarea,$sessarea);
       } 
/*--------------------------------------------------------------------------------------------*/ 
/**
  *create all date selection elements
  */
 
  $this->objdate = $this->newObject('datepicker','htmlelements');
  $name = 'txtdate';
  $datevalue = date('d-m-Y');
  $format = 'DD-MM-YYYY';
  $this->objdate->setName($name);
  $this->objdate->setDefaultDate($datevalue);
  $this->objdate->setDateFormat($format); 

/*------------------------------------------------------------------------------*/ 

/**
  *create all dropdown list
  */
   //All types of activities
   $this->objactivitydropdown  = new dropdown('activityvalues');
   $this->objactivitydropdown->addOption('Please select an activity','Please select an activity') ;
   $this->objactivitydropdown->addOption('School Visitation','School Visitation') ;
   $this->objactivitydropdown->addOption('University Open Day','University Open Day') ;
   $this->objactivitydropdown->addOption('Matric Camps','Matric Camps') ;
   $this->objactivitydropdown->addOption('Cape Careers Exhibitions','Cape Careers Exhibitions') ;
   $this->objactivitydropdown->addOption('Individual visit to SLU office','Individual visit to SLU office') ;
   $this->objactivitydropdown->addOption('Other','Other') ;
//   $this->objactivitydropdown->size = 50;
   
   //All province names  
   $this->objprovincedropdown  = new dropdown('province');
   $this->objprovincedropdown->addOption('Please select a province','Please select a province') ;
   $this->objprovincedropdown->addOption('Western Cape','Western Cape') ;
   $this->objprovincedropdown->addOption('Eastern Cape','Eastern Cape') ;
   $this->objprovincedropdown->addOption('Northern Cape','Northern Cape') ;
   $this->objprovincedropdown->addOption('Gauteng','Gauteng') ;
   $this->objprovincedropdown->addOption('Kwazulu Natal','Kwazulu Natal') ;
   $this->objprovincedropdown->addOption('Free State','Free State') ;
   $this->objprovincedropdown->addOption('Mpumalanga','Mpumalanga') ;
   $this->objprovincedropdown->addOption('Limpopo Province','Limpopo Province') ;
   $this->objprovincedropdown->addOption('North-West Province','North-West Province') ;
  // $this->objprovincedropdown->size = 60;

/*------------------------------------------------------------------------------*/   
   /**
    *create a next button
    */
    $this->objButtonNext  = new button('activitiesnext', $str1);
    $this->objButtonNext->setToSubmit();       

/*------------------------------------------------------------------------------*/    
   
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
  $myTable->addCell(ucfirst($activity));
  $myTable->addCell($this->objactivitydropdown->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($school));
  $myTable->addCell($searchlist->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($area));
  $myTable->addCell($arealist->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($province));
  $myTable->addCell($this->objprovincedropdown->show());
  $myTable->endRow();       
  
  $myTable->startRow();
  $myTable->addCell($this->objButtonNext->show());
  $myTable->endRow();

/*------------------------------------------------------------------------------*/  
  
  /**
    *create a form to place all elements in
    */
  $this->loadClass('form','htmlelements');
  $objForm = new form('sluactivities',$this->uri(array('action'=>'showschoolist')));
  $objForm->displayType = 3;
  $objForm->addToForm($this->objMainheading->show() . '<br>' . $myTable->show());

/*------------------------------------------------------------------------------*/  
          
  /**
    *display the slu activity interface all contents to screen
    */
                                
    echo  $objForm->show();   
 
?>
