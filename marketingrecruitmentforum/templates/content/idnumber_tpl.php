<?php
//template for searching for student id, determine if student exist within db
       
       /**
        *load classes
        */               
       $this->loadClass('button','htmlelements');
/*----------------------------------------------------------------------------*/       
      /**
       *create form heading
       */
       $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
       $this->objMainheading->type=1;
       $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_studentcardinterface','marketingrecruitmentforum');
/*----------------------------------------------------------------------------*/
      /**
       *define language item elements
       */
       $idnumber  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_idnumber','marketingrecruitmentforum');
       $str1  = $this->objLanguage->languageText('word_search');              
/*----------------------------------------------------------------------------*/
      /**
       *create search button
       */
       $this->objsearch  = new button('search', $str1);
       $this->objsearch->setToSubmit();             
       
       $this->objcontinue  = new button('continue', 'No ID Number Available');
       $this->objcontinue->setToSubmit();
/*----------------------------------------------------------------------------*/
      /**
       *create textbook
       */
       $this->objtxtidnumber = $this->newObject('textinput','htmlelements'); 
       $this->objtxtidnumber->name   = "idnumber";
       $this->objtxtidnumber->value  = "";             
/*----------------------------------------------------------------------------*/
      /**
       *create a form to place all elements on
       */
       $objForm = new form('idsearch',$this->uri(array('action'=>'searchidnumber')));
       $objForm->displayType = 3;
       $objForm->addToForm($this->objMainheading->show() . '<br />'. $idnumber . ' ' .  $this->objtxtidnumber->show()  . ' ' . $this->objsearch->show() . '<br />' . '<br/>'."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp".$this->objcontinue->show());
                       
/*----------------------------------------------------------------------------*/
      //display info to the screan
      
      echo $objForm->show();
      //echo  $idnumber . ' ' .  $this->objtxtidnumber->show()  . ' ' . $this->objsearch->show();       
?>
