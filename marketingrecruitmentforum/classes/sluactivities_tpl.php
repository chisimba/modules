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

/*------------------------------------------------------------------------------*/  
  
/**
  *create all language elements
  */
  
  $date = $this->objLanguage->languageText('word_date');
  $activity = $this->objLanguage->languageText('word_activity');
  $school = $this->objLanguage->languageText('phrase_schoolname');
  $area = $this->objLanguage->languageText('word_area');
  $province = $this->objLanguage->languageText('word_province');
  $btnNext  = $this->objLanguage->languageText('word_next');
  $str1 = ucfirst($btnNext);
/*------------------------------------------------------------------------------*/  

/**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_sluactivities','marketingrecruitmentforum');

/*------------------------------------------------------------------------------*/  
  
/**
  *create all textinputs
  */
//   $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
//   $values  = $this->objschoolname->readfiledata();
     
/*--------------------------------------------------------------------------------------------*/               
       //create an object of the schoolnames class
       //call the function that sets the session
       //call the session
       //populate list with values in the session array 
       $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
       $this->objschoolname->readfiledata();
        
       $searchlist  = new dropdown('schoollistactivity');
       $shoolvalues = $this->getSession('schoolnames');
       sort($shoolvalues);
       
       foreach($shoolvalues as $sessschool){
          
          $searchlist->addOption($sessschool,$sessschool);
       }
       
/*--------------------------------------------------------------------------------------------*/       
/**
 *create a dropdown list with all area values
 */
    //$this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
    $this->objschoolname->readareadata();
       
    $arealist = new dropdown('area');
    $areavalues = $this->getSession('area');
  // var_dump($areavalues);
 //   die;
    sort($areavalues);
    
    foreach($areavalues as $sessArea){
        
          $arealist->addOption($sessArea,$sessArea);
    } 
/*--------------------------------------------------------------------------------------------*/ 
/**
  *create all date selection elements
  */
 
  $this->objdate = $this->newObject('datepicker','htmlelements');
  $name = 'txtdate';
  $datevalue = date('Y-m-d');
  $format = 'YYYY-MM-DD';
  $this->objdate->setName($name);
  $this->objdate->setDefaultDate($datevalue);
  $this->objdate->setDateFormat($format); 

/*------------------------------------------------------------------------------*/ 

/**
  *create all dropdown list
  */
        
              
   $this->objactivitydropdown  = new dropdown('activityvalues');  //need exact info regarding activities
   $this->objactivitydropdown->addOption('School Visitation','School Visitation') ;
   $this->objactivitydropdown->addOption('University Open Day','University Open Day') ;
   $this->objactivitydropdown->addOption('Matric Camps','Matric Camps') ;
   $this->objactivitydropdown->addOption('Cape Careers Exhibitions','Cape Careers Exhibitions') ;
   $this->objactivitydropdown->addOption('Individual visit to SLU office','Individual visit to SLU office') ;
   $this->objactivitydropdown->addOption('Other','Other') ;
   $this->objactivitydropdown->size = 50;
   
   $this->objareadropdown  = new dropdown('area');  //get info from abdul - box   --  check in link
   $this->objareadropdown->addOption('Area 1','Area 1') ;
   $this->objareadropdown->addOption('Area 2','Area 2') ;
   $this->objareadropdown->addOption('Area 3','Area 3') ;
   $this->objareadropdown->addOption('Area 4','Area 4') ;
   $this->objareadropdown->addOption('Area 5','Area 5') ;
   $this->objareadropdown->size = 50;
   
   $this->objprovincedropdown  = new dropdown('province');  //get info from abdul - box --  check in link
   $this->objprovincedropdown->addOption('Western Cape','Western Cape') ;
   $this->objprovincedropdown->addOption('Eastern Cape','Eastern Cape') ;
   $this->objprovincedropdown->addOption('Northern Cape','Northern Cape') ;
   $this->objprovincedropdown->addOption('Gauteng','Gauteng') ;
   $this->objprovincedropdown->addOption('Kwazulu Natal','Kwazulu Natal') ;
   $this->objprovincedropdown->addOption('Free State','Free State') ;
   $this->objprovincedropdown->addOption('Mpumalanga','Mpumalanga') ;
   $this->objprovincedropdown->addOption('Limpopo Province','Limpopo Province') ;
   $this->objprovincedropdown->addOption('North-West Province','North-West Province') ;
   $this->objprovincedropdown->size = 50;

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
