<?php
//create a template that shows all SD Cases of student info cards captured
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
       
       $total = $this->objstudcard->allsdcases();
       $totcount  = '';
       //get  the total of all sd cases
       foreach($total as $sessCount){
          
          $totcount[] = $totcount + $sessCount['sdcase'];
       }
       $count = count($totcount);
       $displaytot  = ':' . $count;
/*------------------------------------------------------------------------------*/       
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_reportsd','marketingrecruitmentforum');
        
        $this->objTotheading =& $this->newObject('htmlheading','htmlelements');
        $this->objTotheading->type=3;
        $this->objTotheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_totalsd','marketingrecruitmentforum') .$displaytot ;
        
        $this->objdate =& $this->newObject('htmlheading','htmlelements');
        $this->objdate->type=3;
        $this->objdate->str=$objLanguage->languageText('word_date'). ':' .date('Y-m-d');
/*------------------------------------------------------------------------------*/
       
      /**
       *display all students that are sd cases
       */             
       $sdcases     =   $this->objreport->displaysdcases();
/*------------------------------------------------------------------------------*/
  
      /**
       *display info to screen
       */
       echo $this->objMainheading->show() . '<br />' .$this->objTotheading->show();
       echo $this->objdate->show();
       echo '<br />' . $sdcases;
/*------------------------------------------------------------------------------*/                    
?>
