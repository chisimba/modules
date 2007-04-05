<?php
//template used to search for existing school if it exist
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

//       $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
//       $schoolnames = $this->objfaculties->getSchools();
       $this->schoolnames =& $this->getObject('schoolnames','marketingrecruitmentforum');
       $schoolnames = $this->schoolnames->readfiledata();

       for($i=0; $i < count($schoolnames); $i++){
            $schoolvalues[$i]=$schoolnames[$i];
       }
       //create dropdown list
       $list  = new dropdown('schoolname');
      // $list->size = 50;

       sort($schoolvalues);
       foreach($schoolvalues as $sessschool){
          $list->addOption(NULL, ''.'Select a school from the list');
          $list->addOption($sessschool,$sessschool);
       }
       $list->extra = ' onChange="document.school.submit()"';
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
?>
