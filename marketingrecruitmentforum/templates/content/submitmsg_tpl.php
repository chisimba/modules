<?php


  /**
   *Template used for displaying a msg when user clicks submit
   */     
  
  /**
   *load all classes and create all objects
   */
    $this->loadClass('button','htmlelements');  
    $this->loadClass('tabbedbox', 'htmlelements');
    $this->loadClass('link', 'htmlelements');

  /*----------------------------------------------------------------------------------------*/     
  
  /**
   *create all form language items
   */           
    $submit = $this->objLanguage->languageText('word_submit');
    $str1 = ucfirst($submit);
    
    $editstud = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editstud', 'marketingrecruitmentforum');
    $editslu = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editslu', 'marketingrecruitmentforum');
    $editschool = $this->objLanguage->languageText('mod_marketingrecruitmentforum_editsschool', 'marketingrecruitmentforum');
    $submsg = $this->objLanguage->languageText('mod_marketingrecruitmentforum_submsg', 'marketingrecruitmentforum');

    $editStudLink = new link($this->uri(array('action' => 'editstudcard', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editStudLink->link = $editstud;
    
    $editSLUlink = new link($this->uri(array('action' => 'editsluactivity', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editSLUlink->link = $editslu;
    
    $editSchoollink = new link($this->uri(array('action' => 'editschool', 'module' => 'marketingrecruitmentforum', 'linktext' => 'edit')));
    $editSchoollink->link = $editschool;
  /*----------------------------------------------------------------------------------------*/ 

 /**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_process','marketingrecruitmentforum');
 /*----------------------------------------------------------------------------------------*/ 
  
  /**
   *create all form buttons
   */
        
  $this->objSubmitstudcard  = new button('submitstudcard', $str1);
  $this->objSubmitstudcard->setToSubmit();
  /*----------------------------------------------------------------------------------------*/
   
   /**
    *determine if session variables containing student card data, activities data and school data is empty or not 
    *if !empty then user has not submited information as yet
    *if empty, information submitted    
    */           
     
    $sessionstudcard [] = $this->getSession('studentdata');
    $sessionsluactivity [] = $this->getSession('sluactivitydata');
    $sessionsschoolist [] = $this->getSession('schoolvalues');
    
    
    
   if((!empty($sessionstudcard) )|| (!empty($sessionsluactivity)) || (!empty($sessionsschoolist))){
   
          $hasSubmitted = 'no';
   }else{
          $hasSubmitted = 'yes';
   }        
  /*----------------------------------------------------------------------------------------*/
        echo  $this->objMainheading->show();
      
      if($hasSubmitted == 'yes'){    
          $objForm = new form('submitinfo',$this->uri(array('action'=>'submitinfo')));
          $objForm->displayType = 3;
          $objForm->addToForm("");
              
        
      }else{
         $objForm = new form('submitinfo',$this->uri(array('action'=>'submitinfo','submitmsg' => 'yes')));
          $objForm->displayType = 3;
          $objForm->addToForm(""); 
      
      }

/**************************************************************************************************/ /*PROBLEM*/                  
  //$submitmsg = " ";
if($submitmsg == 'yes'){
            $tomsg =& $this->newObject('timeoutmessage', 'htmlelements');
            $tomsg->setMessage($submsg);
            $tomsg->setTimeout('10000');
            echo $tomsg->show();
  
}
/*----------------------------------------------------------------------------------------*/  
echo  $objForm->show();

?>
