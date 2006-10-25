<?php

//template used to display all search facilities for student cards relevant to faculty
      
      /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objactivity  = & $this->newObject('searchactivities','marketingrecruitmentforum');
       $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
       
/*------------------------------------------------------------------------------*/       
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_activities','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/  
      /**
        *define all language items
        */
        $instruction = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchhhelp','marketingrecruitmentforum');
        $click = $this->objLanguage->languageText('mod_marketingrecruitmentforum_click','marketingrecruitmentforum');
        $go = $this->objLanguage->languageText('word_go');
        
        $infomsg = $instruction . '<br />' . $click ;
        
/*------------------------------------------------------------------------------*/   
      /**
       *create form button -- go
       */
                    
      $this->objButtonGo  = new button('go', $go);
      $this->objButtonGo->setToSubmit();

/*------------------------------------------------------------------------------*/              
    /**
     *create dropdwonlist with all schoolnames
     */
       //create an object of the schoolnames class
       //call the function that sets the session
       //call the session
       //populate list with values in the session array 
       
       $this->objschoolname->readfiledata();
       
       $schoollist  = new dropdown('schoollistnames');
       $shoolvalues = $this->getSession('schoolnames');
       sort($shoolvalues);
       foreach($shoolvalues as $sessschool){
          $schoollist->addOption($sessschool,$sessschool);
       }  
/*------------------------------------------------------------------------------*/
    /**
     *create datepicker objects
     */           
        $this->objdate = $this->newObject('datepicker','htmlelements');
        $name = 'fromdate';
        $datevalue = date('Y-m-d');
        $format = 'YYYY-MM-DD';
        $this->objdate->setName($name);
        $this->objdate->setDefaultDate($datevalue);
        $this->objdate->setDateFormat($format);
        
        $this->objtodate = $this->newObject('datepicker','htmlelements');
        $name = 'todate';
        $datevalue = date('Y-m-d');
        $format = 'YYYY-MM-DD';
        $this->objtodate->setName($name);
        $this->objtodate->setDefaultDate($datevalue);
        $this->objtodate->setDateFormat($format); 
    
/*------------------------------------------------------------------------------*/
    /**
     *call to all functions from class searchstudcard
     */         
      $results =  $this->objactivity->getAllactivities();   
      $activdates  = $this->objactivity->getactivdate();
      $activtype  = $this->objactivity->activitytype(); 
      $province = $this->objactivity->activitybyprov();
      $area  = $this->objactivity->activitybyarea();
      $school = $this->objactivity->activitybyschool();
      
/*------------------------------------------------------------------------------*/
    /**
     *create tabpan and display search info
     */         
    $daterange  = $this->objdate->show() . ' ' . $this->objtodate->show(); 
    $Activityinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $Activityinfo->tabName = 'ActivityInfo';
 
    $Activityinfo->addTab('activity', 'All activities',$results);
    $Activityinfo->addTab('dates', 'Actiities between dates',$daterange .'<br />' .$activdates);
    $Activityinfo->addTab('type', 'All activities by type ',$activtype);
    $Activityinfo->addTab('province', 'Activities by province',$province);
    $Activityinfo->addTab('area', 'Activities by area',$area);
    $Activityinfo->addTab('school', 'Activities by school','Select a school to search by' . ' ' .$schoollist->show() . ' '.$this->objButtonGo->show().' <br />'. '<br />' . $school);
    
    
/*-------------------------------------------------------------------------------*/
   
  /**
   *create a form to place all elements in
   */
  // $val  = $this->objsearchinfo->activitysearch();
   $objForm = new form('searchactivity',$this->uri(array('action'=>'NULL')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show() . '<br />' . '<br />'. $infomsg . ' ' . '<br />' . '<br />' . $Activityinfo->show() . '<br />');
    
   echo $objForm->show();
/*------------------------------------------------------------------------------*/   
?>
