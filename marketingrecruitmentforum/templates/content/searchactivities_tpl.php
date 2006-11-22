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
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_activities1','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/  
      /**
        *define all language items
        */
        $instruction = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchhhelp','marketingrecruitmentforum');
        $click = $this->objLanguage->languageText('mod_marketingrecruitmentforum_click','marketingrecruitmentforum');
        $displayactivities = $this->objLanguage->languageText('mod_marketingrecruitmentforum_display','marketingrecruitmentforum');
        
        $infomsg = $instruction . '<br />' . $click ;
        
/*------------------------------------------------------------------------------*/   
      /**
       *create form button -- go
       */
                    
      $this->objButtonGo  = new button('searchactiv', $displayactivities);
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
       
          $schoollist->addOption(NULL, ''.'[ Select A School from the list ]');
          $schoollist->addOption($sessschool,$sessschool);
      }  
          $schoollist->setSelected($this->getParam('schoollistnames'));
          $schoollist->extra = ' onChange="document.searchactivity.submit()"';
      
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
      $activdates  = $this->objactivity->getactivdate($activitydate);
      $activtype  = $this->objactivity->activitytype(); 
      $province = $this->objactivity->activitybyprov();
      $area  = $this->objactivity->activitybyarea();
      $school = $this->objactivity->activitybyschool($activschool);
      
    /**
     *create a table to place date elements in
     */
      $mydateTab =& $this->newObject('htmltable', 'htmlelements');
      $mydateTab->cellspacing = '1';
      $mydateTab->cellpadding = '2';
      $mydateTab->border='0';
      $mydateTab->width = '50%';
      
      $mydateTab->startRow();
      $mydateTab->addCell('Start Date');
      $mydateTab->addCell($this->objdate->show());
      $mydateTab->endRow();  
      
      $mydateTab->startRow();
      $mydateTab->addCell('End Date');
      $mydateTab->addCell($this->objtodate->show());
      $mydateTab->endRow();
      
      $mydateTab->startRow();
      $mydateTab->addCell('');
      $mydateTab->addCell($this->objButtonGo->show());
      $mydateTab->endRow();
      
/*------------------------------------------------------------------------------*/
    /**
     *create tabpan and display search info
     */         
    //$daterange  = 'Start Date' . ' ' .$this->objdate->show() . ' ' . 'End Date'.$this->objtodate->show() . ' ' .$this->objButtonGo->show();
    //$startdate  = 'Start Date' . ' ' .$this->objdate->show();
    //$enddate  = 'End Date' . ' ' .$this->objdate->show();
     
    $Activityinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $Activityinfo->tabName = 'ActivityInfo';
 
    $Activityinfo->addTab('activity', 'All Activities',$results.'<br />');
    $Activityinfo->addTab('dates', 'Activities By Dates',$mydateTab->show().' <br />'. '<br />' . $activdates.'<br />');
    $Activityinfo->addTab('type', 'All Activities By Type ',$activtype.'<br />');
    $Activityinfo->addTab('province', 'Activities By Province',$province.'<br />');
    $Activityinfo->addTab('area', 'Activities By Area',$area.'<br />');
    $Activityinfo->addTab('school', 'Activities By School','<b>'.'Select a school to search by'.'</b>' . ' ' .$schoollist->show().' <br />'. '<br />' . $school.'<br />');
    
    
/*-------------------------------------------------------------------------------*/
   
  /**
   *create a form to place all elements in
   */
  // $val  = $this->objsearchinfo->activitysearch();
   $objForm = new form('searchactivity',$this->uri(array('action'=>'showstudschoolactivity')));
   $objForm->displayType = 3;
   $objForm->addToForm("<center>".$this->objMainheading->show() . '<br />' . '<br />'.'<b>'.'<i>'. $infomsg .'</i>'.'</b>'."</center>". ' ' . '<br />' . '<br />' . $Activityinfo->show() );
    
   echo $objForm->show()."<br />";
/*------------------------------------------------------------------------------*/   
?>
