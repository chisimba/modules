<?php
//template used to send any follow up communication to students
  
  /**
   *create form heading
   */
    $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
    $this->objMainheading->type=1;
    $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_followup','marketingrecruitmentforum');    
    
    $dear = $this->objLanguage->languageText('word_dear');
    $thxmsg =  $this->objLanguage->languageText('mod_marketingrecruitmentforum_thxmsg','marketingrecruitmentforum');
/*------------------------------------------------------------------------------------------------------------------------------*/
    /**
     *get Student details from db
     */
     $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
     $test  = $this->objstudcard->getstudInfo();
     
     /*foreach($test as $sessTest)
     {
        $name = $sessTest['name'];
        $surname  = $sessTest['surname'];
        $addy = $sessTest['postaddress'];
     }  */       
     $string  = $name . ' '. $surname;
/*------------------------------------------------------------------------------------------------------------------------------*/
    /**
     *create a tabpane to display 
     */             
/*------------------------------------------------------------------------------------------------------------------------------*/    
    echo  $this->objMainheading->show() ;
    echo  '<br />'. '<br />'."<div align=\"right\">" . $addy.   "</div>";
    echo  '<br />' . $dear  . ' ' . strtoupper($string);
    echo  '<br />' .  '<br />' .  $thxmsg;
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah ';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah ';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah ';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah ';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah ';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah ';
?>
