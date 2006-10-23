<?php

//template used to display all search facilities for student cards relevant to faculty
      
      /**
       *load all classes
       */
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objschool = & $this->getObject('searchschools','marketingrecruitmentforum');   
/*------------------------------------------------------------------------------*/       
                    
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_schools','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/  
      /**
        *define all language items
        */
        $searchmsg = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinstruction','marketingrecruitmentforum');
        $go = $this->objLanguage->languageText('word_go');
/*------------------------------------------------------------------------------*/  
      /**
       *create form button -- go
       */
                    
      $this->objButtonGo  = new button('go', $go);
      $this->objButtonGo->setToSubmit();
/*------------------------------------------------------------------------------*/ 

    /**
     *call to all functions from class searchstudcard
     */         
      $results =  $this->objschool->getAllschools();   
      $schoolname  = $this->objschool->schoolbyname();
      $area  = $this->objschool->schoolbyarea(); 
      $province = $this->objschool->activitybyprov();
      
      
/*------------------------------------------------------------------------------*/
    /**
     *create tabpan and display search info
     */         
    $schoolinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $schoolinfo->tabName = 'ActivityInfo';
 
    $schoolinfo->addTab('schoollist', 'All Schools',$results);
    $schoolinfo->addTab('schoolname', 'School By Name',$schoolname);
    $schoolinfo->addTab('areaschool', 'Schools By Area',$area);
    $schoolinfo->addTab('schoolprovince', 'Schools By Province',$province);
    //$Studcardinfo->addTab('area', 'Activities by area',$area);
    //$Studcardinfo->addTab('school', 'Activities by school','Select a school to search by' . ' ' .$schoollist->show() . ' '.$this->objButtonGo->show().' <br />'. '<br />' . $school);
    
    
/*-------------------------------------------------------------------------------*/
  
  /**
   *create a form to place all elements in
   */
   // $val  = $this->objsearchinfo->schoolsearch();
   $objForm = new form('searchsschool',$this->uri(array('action'=>'NULL')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show() . '<br />' . '<br />'. $searchmsg .'<br />' . '<br />' . $schoolinfo->show());
    
   echo $objForm->show();
/*------------------------------------------------------------------------------*/   
 
?>
