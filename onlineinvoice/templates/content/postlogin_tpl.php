<?php
  /**
   *template for post-login form
   */
/************************************************************************************************************************************************/
   /**
  *load all classes
  */
  
  $this->loadClass('htmlheading','htmlelements');
  $this->loadClass('button','htmlelements');
  $this->loadClass('textinput','htmlelements');
  $this->loadClass('mouseoverpopup','htmlelements');
  $this->loadClass('tabbedbox','htmlelements');
  $this->loadClass('featurebox','navigation');
  $this->loadClass('form','htmlelements');
  $this->loadClass('htmltable','htmlelements');
  $this->objUser =& $this->getObject('user', 'security');
  $this->objLogger =& $this->getObject('loggedInUsers', 'security');
  
  $userId = $this->objUser->getUserId($this->objUser->fullname());
  
          //$myTab = $this->newObject('htmltable','htmlelements');
/************************************************************************************************************************************************/

  /**
   *create all form headings
   */    
   
  $objloginHeading  = new htmlheading('Mainheading'); 
  $objloginHeading->type = 2;
  $objloginHeading->str=ucfirst($objLanguage->languageText('mod_onlineinvoice_mainheading','onlineinvoice'));
/************************************************************************************************************************************************/
  /**
   *create all language items
   */
   $welcome = ucfirst($objLanguage->languageText('word_welcome'));
   $lastacces = ucfirst($objLanguage->languageText('mod_onlineinvoice_lastaccess','onlineinvoice'));
   $invoicesubmitted  = ucfirst($objLanguage->languageText('mod_onlineinvoice_invoicesubmitted','onlineinvoice'));
   $numlogin  = ucfirst($objLanguage->languageText('mod_onlineinvoice_numlogin','onlineinvoice'));
        
/************************************************************************************************************************************************/
  /**
   *create a table to place all elements in
   */
   
        //$myTab = $this->newObject('htmltable','htmlelements');
     $myTab = new htmltable('myTab');   
     $myTab->width='150%';
     $myTab->border='0';
     $myTab->cellspacing='5';
     $myTab->cellpadding='5';
   
     $myTab->startRow();
     $myTab->addCell(ucfirst($welcome . ' : '. strtoupper($this->objUser->fullname() )));
     $myTab->endRow();
     
     $myTab->startRow();
     $myTab->addCell(ucfirst($lastacces .' : '. 'NEED TO BE FIXED AND KNW HOW'));//. ':' . ucfirst($this->objUser->getLastLoginDate($userId))));
     $myTab->endRow();
     
     $myTab->startRow();
     $myTab->addCell(ucfirst($invoicesubmitted) . ' :');
     $myTab->endRow();
     
     $myTab->startRow();
     $myTab->addCell(ucfirst($numlogin . ': '. $this->objUser->logins($userId)));
     $myTab->endRow();
     
/************************************************************************************************************************************************/
  /**
   *create a form to place all objects on
   */    
  // $this->loadClass('budget','onlineinvoice'); 
  //  $objBudget  = new budgetinfo('Mainheading');
  //  $values = $objBudget->budget();
  
  $objpostloginForm = new form('postlogin',$this->uri(array('action'=>'NULL')));  
  $objpostloginForm->id = 'postlogin';
  $objpostloginForm->displayType = 3;
  $objpostloginForm->addToForm($objloginHeading->show() .  '<br />' . '<br />'  . $myTab->show());// . $values);
  
  
/************************************************************************************************************************************************/
    
  /**
   *display all form outputs
   */
  
     echo $objpostloginForm->show();
     
   
?>
