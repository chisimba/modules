<?php
//template used to create a report that determines all SD CASES 

//template used to display all search facilities for student cards relevant to faculty
      
      /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objreport  = & $this->newObject('reportinfo','marketingrecruitmentforum');
      // $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
       
/*------------------------------------------------------------------------------*/       
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_reportdata','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/  

    /**
     *call to all functions from class searchstudcard
     */         
      $sdcases     =   $this->objreport->displaysdcases();   
      $results  =  $this->objreport->entryQualification();
      $facultyinterest  = $this->objreport->facultyinterest(); 
     // $province = $this->objactivity->activitybyprov();
     // $area  = $this->objactivity->activitybyarea();
     // $school = $this->objactivity->activitybyschool();
      
/*------------------------------------------------------------------------------*/
    /**
     *create tabpan and display search info
     */         
    $Reportinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $Reportinfo->tabName = 'ActivityInfo';
 
    $Reportinfo->addTab('faculty', 'Students interested in a faculty',$facultyinterest);
    $Reportinfo->addTab('sdcase', 'All SD Cases', '<br />' .$sdcases);
    $Reportinfo->addTab('results', 'All students that qualify for entry ',$results);
    //$Reportinfo->addTab('province', 'Activities by province',$province);
    //$Reportinfo->addTab('area', 'Activities by area',$area);
    //$Reportinfo->addTab('school', 'Activities by school','Select a school to search by' . ' ' .$schoollist->show() . ' '.$this->objButtonGo->show().' <br />'. '<br />' . $school);
    
    
/*-------------------------------------------------------------------------------*/
   
  /**
   *create a form to place all elements in
   */
  // $val  = $this->objsearchinfo->activitysearch();
   $objForm = new form('searchactivity',$this->uri(array('action'=>'NULL')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show() . '<br />' . '<br />'. '<br />' . '<br />' . $Reportinfo->show());
    
   echo $objForm->show();
/*------------------------------------------------------------------------------*/   


?>
