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
        $searchmsg = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoolhelp','marketingrecruitmentforum');
        $click = $this->objLanguage->languageText('mod_marketingrecruitmentforum_click','marketingrecruitmentforum');
        $go = $this->objLanguage->languageText('word_go');
        
        $instruction  = $searchmsg . '<br />' . $click;
/*------------------------------------------------------------------------------*/  
      /**
       *create form button -- go
       */
                    
      $this->objButtonGo  = new button('go', $go);
      $this->objButtonGo->setToSubmit();
/*------------------------------------------------------------------------------*/
       //create an object of the schoolnames class
       //call the function that sets the session
       //call the session
       //populate list with values in the session array 
       $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
       $this->objschoolname->readfiledata();
        
       $searchlist  = new dropdown('namevalues');
       $shoolvalues = $this->getSession('schoolnames');
       sort($shoolvalues);
       foreach($shoolvalues as $sessschool){
          $searchlist->addOption(NULL, ''.'[ Select A School from the list ]');
          $searchlist->addOption($sessschool,$sessschool);
       }
       $searchlist->setSelected($this->getParam('namevalues'));
       $searchlist->extra = ' onChange="document.searchsschool.submit()"';
       
/*--------------------------------------------------------------------------------------------*/       
 

    /**
     *call to all functions from class searchstudcard
     */         
      $results =  $this->objschool->getAllschools();   
      $schoolname  = $this->objschool->schoolbyname($schoolbyname);
      $area  = $this->objschool->schoolbyarea(); 
      $province = $this->objschool->activitybyprov();
      
      
/*------------------------------------------------------------------------------*/
    /**
     *create tabpan and display search info
     */         
    $schoolinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $schoolinfo->tabName = 'ActivityInfo';
 
    $schoolinfo->addTab('schoollist', 'All Schools',$results);
    $schoolinfo->addTab('schoolname', 'School By Name','<br />' .'<b>'.'Select a school to search by'.'</b>'. ' '. $searchlist->show() . '<br />' . '<br />' .$schoolname);
    $schoolinfo->addTab('areaschool', 'Schools By Area',$area);
    $schoolinfo->addTab('schoolprovince', 'Schools By Province',$province);
    //$Studcardinfo->addTab('area', 'Activities by area',$area);
    //$Studcardinfo->addTab('school', 'Activities by school','Select a school to search by' . ' ' .$schoollist->show() . ' '.$this->objButtonGo->show().' <br />'. '<br />' . $school);
    
    
/*-------------------------------------------------------------------------------*/
  
  /**
   *create a form to place all elements in
   */
   // $val  = $this->objsearchinfo->schoolsearch();
   $objForm = new form('searchsschool',$this->uri(array('action'=>'showschoolbyname')));
   $objForm->displayType = 3;
   $objForm->addToForm("<center>".$this->objMainheading->show() . '<br />' . '<br />'.'<b>'.'<i>'. $instruction.'</i>'.'</b>'."</center>" .'<br />' . '<br />' . $schoolinfo->show() . '<br />' . '<br />');
    
   echo $objForm->show();
/*------------------------------------------------------------------------------*/   
 
?>
