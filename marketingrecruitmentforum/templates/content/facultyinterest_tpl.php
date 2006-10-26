<?php
//create a template to display report for all students in a faculty

     /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('htmlheading','htmlelements');
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('dropdown','htmlelements');   
       
       //get faculty name
      foreach($faculty as $sessfacval){
      
          $displayname  = $sessfacval['faculty'];
          $count  = $sessfacval['totstud'];
      }
           
       
/*------------------------------------------------------------------------------*/       
       /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_reportfaculty','marketingrecruitmentforum');
        
        $this->objnamehead =& $this->newObject('htmlheading','htmlelements');
        $this->objnamehead->type=5;
        $this->objnamehead->str=$objLanguage->languageText('mod_marketingrecruitmentforum_facultyname','marketingrecruitmentforum') .':' . ' '.$displayname;//
        
        $this->objtotstud =& $this->newObject('htmlheading','htmlelements');
        $this->objtotstud->type=5;
        $this->objtotstud->str=$objLanguage->languageText('mod_marketingrecruitmentforum_totstud','marketingrecruitmentforum')  . ':'. ' ' .$count;
        
/*------------------------------------------------------------------------------*/
      /**
       *create dropdownlist with faculty values
       */
       $names = new dropdown('names');
       $facultynames  = $this->getSession('faculty');
       foreach($facultynames as $sessfac){
            
            $names->addOption($sessfac,$sessfac);
       }
       $names->extra = ' onChange="document.reportfaculty.submit()"';
       
       //dropdown heading
        $this->objheading =& $this->newObject('htmlheading','htmlelements');
        $this->objheading->type=5;
        $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_facultymsg','marketingrecruitmentforum') .' '. $names->show();
/*------------------------------------------------------------------------------*/
      
      
      
      $this->objstudcard  = & $this->newObject('searchstudcard','marketingrecruitmentforum');
      $facultydetails = $this->objstudcard->countstudfaculty($faculty);
      
     // var_dump($facultydetails);
     // die;
/*------------------------------------------------------------------------------*/
            
             
/*------------------------------------------------------------------------------*/
    /**
     *create a form to place all elements on
     */
      $objForm = new form('reportfaculty',$this->uri(array('action'=>'reportdropdown')));
      $objForm->displayType = 3;
      $objForm->addToForm($this->objMainheading->show() .'<br />' . '<br />'.$this->objheading->show(). '<br />' . $this->objnamehead->show() .'<br />' . $this->objtotstud->show().'<br />' .'<br />' .$facultydetails);
/*-------------------------------------------------------------------------------*/        
      /**
       *display contents to screen
       */
       echo  $objForm->show() . '<br />';   
               
/*------------------------------------------------------------------------------*/            
?>
