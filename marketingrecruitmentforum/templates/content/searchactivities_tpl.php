<?php

//template used to display all search facilities for student cards relevant to faculty
      
      /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objactivity  = & $this->newObject('searchactivities','marketingrecruitmentforum');
       $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
       
       $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
       
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
      
      $this->objGo  = new button('searchgo', 'Go');
      $this->objGo->setToSubmit();

/*------------------------------------------------------------------------------*/              
      $schoolnames = $this->objfaculties->getSchools(); 
       for($i=0; $i < count($schoolnames); $i++){
            $shoolvalues[$i]=$schoolnames[$i]->SCHOOLNAME;
       }
       //create dropdown list
       $schoollist  = new dropdown('schoollistnames');
       //$schoollist->size = 50;
       
       sort($shoolvalues);
       foreach($shoolvalues as $sessschool){
          $schoollist->addOption(NULL, ''.' Select A School from the list ');
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
     
    $Activityinfo = & $this->newObject('tabcontent','htmlelements');
    $Activityinfo->name = 'ActivityInfo';
    $Activityinfo->width = '750px';
    
    $Activityinfo->addTab('All Activities',$results, false);
    $Activityinfo->addTab('Activities By Dates',$mydateTab->show().' <br />'. '<br />' . $activdates, false);
    $Activityinfo->addTab('Activities By Type ',$activtype, false);
    $Activityinfo->addTab('Activities By Area',$area, false);
    $Activityinfo->addTab('Activities By Province',$province, false);
    $Activityinfo->addTab('Activities By School','<b>'.'Select a school to search by'.'</b>' . ' ' .$schoollist->show().''.$this->objGo->show().' <br />'. '<br />' . $school, false);
    
    
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
