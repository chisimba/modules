<?php
//template used to display all search facilities for student cards relevant to faculty
      
      /**
       *load all classes
       */
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objsearchfac  = & $this->newObject('searchfaculty','marketingrecruitmentforum');
       
/*---------------------------------------------------------------------------------------------------*/       
                    
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_infocardfaculty','marketingrecruitmentforum');
/*---------------------------------------------------------------------------------------------------*/  
      /**
        *define all language items
        */
        $searchmsg = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinstruction','marketingrecruitmentforum');
        

/*---------------------------------------------------------------------------------------------------*/   
      /**
       *create form button -- go
       */
      //$this->objButtonGo  = new button('go', $go);
      //$this->objButtonGo->setToSubmit();
/*---------------------------------------------------------------------------------------------------*/
      /**
       *create faculty list 
       */
       $this->objfaculty  = & $this->getObject('faculty','marketingrecruitmentforum');
       $this->objfaculty->displayfaculty();
       $faculty = new dropdown('facultynameval');
       $facultynames  = $this->getSession('faculty');
       foreach($facultynames as $sessfac){
        
            $faculty->addOption($sessfac,$sessfac);
       }    
       
       $faculty->extra = ' onChange="document.searchsluresults.submit()"';         
/*---------------------------------------------------------------------------------------------------*/
     /**
      *call all class objects to define layout
      */
      $facultyentered = $this->objsearchfac->studentsbyfaculty($facultyval);
      $exemptionfaculty = $this->objsearchfac->exemptionbyfaculty($facultyexmp);
      $facrelsubj = $this->objsearchfac->relsubjbyfaculty($facsubj);
      $coursefaculty  = $this->objsearchfac->coursebyfaculty($faccourse);
      $sdcasefac  = $this->objsearchfac->sdcasebyfaculty($facsdcase);
      //$this->objsearchfac
/*---------------------------------------------------------------------------------------------------*/      
                 
    /**
     *create a tabpane to display data within
     */           
    
    $facultyinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $facultyinfo->tabName = 'OutputInfo';
    
    $facultyinfo->addTab('faculty', 'All students entered for faculty',$facultyentered . '<br />');
    $facultyinfo->addTab('exemption', 'All students with exemption',$exemptionfaculty. '<br />');
    $facultyinfo->addTab('relsubjects', 'All with relevant subjects',$facrelsubj. '<br />');
    $facultyinfo->addTab('course', 'All students per faculty course',$coursefaculty. '<br />');
    $facultyinfo->addTab('sdcase', 'All SD Cases per faculty',$sdcasefac. '<br />');
/*---------------------------------------------------------------------------------------------------*/    
 
  /**
   *create a form to place all elements in
   */
    
   $objForm = new form('searchsluresults',$this->uri(array('action'=>'studcardfaculty')));
   $objForm->displayType = 3;
   $objForm->addToForm("<center>".$this->objMainheading->show(). '<br />' . '<br />'. '<b>'.'<i>' .$searchmsg . ' ' .'</i>'.'</b>'. $faculty->show()."</center>" . '<br />' . '<br />' . $facultyinfo->show());

/*---------------------------------------------------------------------------------------------------*/ 
      
   echo $objForm->show();
?>
