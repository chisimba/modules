<?php
//template to display report showing all students that qualify for entry
    /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('htmlheading','htmlelements');
       $this->loadClass('datepicker','htmlelements');
       
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objreport  = & $this->newObject('reportinfo','marketingrecruitmentforum');
       $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
       $this->objreport  = & $this->newObject('reportinfo','marketingrecruitmentforum');
       
       $total = $this->objstudcard->allstudq();
       //$fac = $this->objstudcard->facultyinterest();
       $totcount  = 0;
       //get  the total of all sd cases
       foreach($total as $sessCount){
          $tot  = $sessCount['entry'];
       }
       //$count = count($totcount);
       $displaytot  = ':' . $tot;
       
       //foreach($fac as $sessfac){
       //   $totcount = $sessfac['totalstudent'];
      // }
/*------------------------------------------------------------------------------*/       
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_entryqualify','marketingrecruitmentforum');
        
        $this->objheading =& $this->newObject('htmlheading','htmlelements');
        $this->objheading->type=3;
        $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_criteria','marketingrecruitmentforum');
        
        $this->objtotal =& $this->newObject('htmlheading','htmlelements');
        $this->objtotal->type=3;
        $this->objtotal->str=$objLanguage->languageText('mod_marketingrecruitmentforum_entrytot','marketingrecruitmentforum') .$displaytot ;
        
        $this->objdate =& $this->newObject('htmlheading','htmlelements');
        $this->objdate->type=3;
        $this->objdate->str=$objLanguage->languageText('word_date'). ':' .date('Y-m-d');
/*------------------------------------------------------------------------------*/
 
      /**
       *display all students that are sd cases
       */             
       $results  =  $this->objreport->entryQualification();
/*------------------------------------------------------------------------------*/
  
      /**
       *display info to screen
       */
       echo $this->objMainheading->show() . '<br />' .$this->objheading->show();
       echo $this->objtotal->show();
       echo $this->objdate->show();
       echo '<br />' . $results;
       //echo $totcount;
/*------------------------------------------------------------------------------*/
?>
