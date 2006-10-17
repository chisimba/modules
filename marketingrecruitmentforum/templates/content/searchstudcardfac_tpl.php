<?php
//template used to display all search facilities for student cards relevant to faculty
      
      /**
       *load all classes
       */
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       
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
    $val  = $this->objsearchinfo->facultysearchlist();
   $objForm = new form('searchslu',$this->uri(array('action'=>'NULL')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show() . '<br />' . '<br />'. $searchmsg . ' ' . $val . ' ' .$this->objButtonGo->show());

/*------------------------------------------------------------------------------*/
      
   echo $objForm->show();
    
       /**
        *display all information to the screen
        */                       
/*        $val  = $this->objsearchinfo->facultysearchlist();
        echo $this->objMainheading->show();
        echo '<br />'  . '<br />';
        echo '<b>' . $searchmsg . '</b>';
        echo ' ' .   $val;*/
?>
