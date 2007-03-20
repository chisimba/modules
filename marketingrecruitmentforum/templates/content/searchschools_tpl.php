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
        $schooselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoolselect','marketingrecruitmentforum');
        
        $instruction  = $searchmsg . '<br />' . $click;
/*------------------------------------------------------------------------------*/  
      /**
       *create form button -- go
       */
                    
      $this->objButtonGo  = new button('go', $go);
      $this->objButtonGo->setToSubmit();
      
      $this->objGo  = new button('searchgo', $go);
      $this->objGo->setToSubmit();
/*------------------------------------------------------------------------------*/
       $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
       
       $schoolnames = $this->objfaculties->getSchools(); 
       for($i=0; $i < count($schoolnames); $i++){
            $schoolvalues[$i]=$schoolnames[$i]->SCHOOLNAME;
       }

       //create dropdown list
       $searchlist  = new dropdown('namevalues');
      // $searchlist->size = 50;
       
       sort($schoolvalues);
       foreach($schoolvalues as $sessschool){
          $searchlist->addOption(NULL, ''.$schooselect);
          $searchlist->addOption($sessschool,$sessschool);
       }
       $searchlist->setSelected($this->getParam('namevalues',NULL));
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
    $schoolinfo = & $this->newObject('tabcontent','htmlelements');
    $schoolinfo->name = 'schooldata';
    $schoolinfo->width = "750px";
 
    $schoolinfo->addTab('All Schools',$results, false);
    $schoolinfo->addTab('School By Name','<br />' .'<b>'.'Select a school to search by'.'</b>'. ' '. $searchlist->show().' '.$this->objGo->show() . '<br />' . '<br />' .$schoolname, false);
    $schoolinfo->addTab('Schools By Area',$area, false);
    $schoolinfo->addTab('Schools By Province',$province, false);
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
