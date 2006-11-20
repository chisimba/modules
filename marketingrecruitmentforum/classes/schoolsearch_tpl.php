<?php
//search for existing school if it exist
/**
       *create form heading
       */
       $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
       $this->objMainheading->type=1;
       $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_captureschool1','marketingrecruitmentforum');
       
       $select =  'Select a school name';
/*----------------------------------------------------------------------------*/       
       /**
        *create dropdown with school values
        */
                       
       $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
       $this->objschoolname->readfiledata();
        
       $list  = new dropdown('schoolname');
       $shoolvalues  = $this->getSession('schoolnames');
       sort($shoolvalues);
       foreach($shoolvalues as $sessschool){
          
          $list->addOption($sessschool,$sessschool);
          $list->extra = ' onChange="document.school.submit()"';
       }
 
/*----------------------------------------------------------------------------*/
      /**
       *create a form to place all elements on
       */

       $objForm = new form('school',$this->uri(array('action'=>'searchschool')));
       $objForm->displayType = 3;
       $objForm->addToForm($this->objMainheading->show() . '<br />'. $select . ' ' .  $list->show() . '<br />'.'<br />' );
                       
/*----------------------------------------------------------------------------*/
      //display info to the screan
      
      echo $objForm->show();
      //echo 'hello';

?>
