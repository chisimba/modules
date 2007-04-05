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
  $this->schoolnames =& $this->getObject('schoolnames','marketingrecruitmentforum');
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
  
  $this->objheading =& $this->newObject('htmlheading','htmlelements');
  $this->objheading->type=5;
  $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_warning','marketingrecruitmentforum');

/*------------------------------------------------------------------------------*/  
/**
  *create a dropdown list containing school values
  */   
      // $schoolvalues = array();
       //$schoolnames = $this->objfaculties->getSchools(); 
    //   for($i=0; $i < count($schoolnames); $i++){
           // $schoolvalues[$i]=$schoolnames[$i]->SCHOOLNAME;
    //   }
       
        $schoolnames = $this->schoolnames->readfiledata();

        for($i=0; $i < count($schoolnames); $i++){
            $schoolvalues[$i]=$schoolnames[$i];
        }
       
       
       $searchlist  = new dropdown('schoollistactivity');
     
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
  
      // $postAreaInfo = $this->objfaculties->getPostInfo(); 
      // for($i=0; $i < count($postAreaInfo); $i++){
      //      $areavals[$i]=$postAreaInfo[$i]->CITY;
      // }
    //   echo "<pre>";
   // print_r($areavals);die;
     //$this->schoolnames =& $this->getObject('schoolnames','marketingrecruitmentforum');
     
      $areadetails = $this->schoolnames->readareadata();
      for($i=0; $i < count($areadetails); $i++){
            $areavals[$i]=$areadetails[$i];
        }
       //create dropdown list
       $arealist  = new dropdown('area');
     
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
   $this->objactivitydropdown->addOption(NULL,'Please select an activity') ;
   $this->objactivitydropdown->addOption('School Visitation','School Visitation') ;
   $this->objactivitydropdown->addOption('University Open Day','University Open Day') ;
   $this->objactivitydropdown->addOption('Matric Camps','Matric Camps') ;
   $this->objactivitydropdown->addOption('Cape Careers Exhibitions','Cape Careers Exhibitions') ;
   $this->objactivitydropdown->addOption('Individual visit to SLU office','Individual visit to SLU office') ;
   $this->objactivitydropdown->addOption('Other','Other') ;

   
   //All province names  
   $this->objprovincedropdown  = new dropdown('province');
   $this->objprovincedropdown->addOption(NULL,'Please select a province') ;
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
  $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$this->objactivitydropdown->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($school));
  $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$searchlist->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($area));
  $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$arealist->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($province));
  $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$this->objprovincedropdown->show());
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
  $objForm->addToForm($this->objMainheading->show() . '<br />'."<span class=error>".'<i>'.$this->objheading->show().'</i>'."</span>".'<br />' . $myTable->show());
  $objForm->addRule('activityvalues','Please select an activity','required');
  $objForm->addRule('schoollistactivity','Please select a school','required');
  $objForm->addRule('area','Please select a area','required');
  $objForm->addRule('province','Please select a province','required');
  
/*------------------------------------------------------------------------------*/  
          
  /**
    *display the slu activity interface all contents to screen
    */
                                
    echo  $objForm->show();   
 
?>
