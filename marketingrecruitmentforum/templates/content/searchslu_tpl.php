<?php
//template used to display all search facilities for SLU Activities
      
      /**
       *load all classes
       */
       $this->loadClass('dropdown', 'htmlelements');
       $this->loadclass('button','htmlelements');
       
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $searchlist  = new dropdown('searchlist');
/*---------------------------------------------------------------------------------------------------*/       
                    
      /**y
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_infocardslu','marketingrecruitmentforum');
/*---------------------------------------------------------------------------------------------------*/  
      /**
        *define all language items
        */
        $searchmsg = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinstruction','marketingrecruitmentforum');
        $go = $this->objLanguage->languageText('word_go');

/*---------------------------------------------------------------------------------------------------*/        
      /**
       *create form button -- go
       */
                    
      $this->objButtonGo  = new button('go', $go);
      $this->objButtonGo->setToSubmit();
/*---------------------------------------------------------------------------------------------------*/
 /**
   *create a form to place all elements in
   */
   $val  = $this->objsearchinfo->slusearchlist();
   $objForm = new form('searchslu',$this->uri(array('action'=>'NULL')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show() . '<br />' . '<br />'. $searchmsg . ' ' . $val . ' ' .$this->objButtonGo->show());

/*------------------------------------------------------------------------------*
       /**
        *display all information to the screen
        */                       
        /*$val  = $this->objsearchinfo->slusearchlist();
        echo $this->objMainheading->show();
        echo '<br />'  . '<br />';
        echo '<b>' . $searchmsg . '</b>';
        echo ' ' .   $val;*/
        echo $objForm->show();

?>
