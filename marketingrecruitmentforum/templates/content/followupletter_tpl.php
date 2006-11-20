<?php
//template used to send any follow up communication to students
  
  /**
   *create form heading
   */
    $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
    $this->objMainheading->type=1;
    $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_followup','marketingrecruitmentforum');    
    
    $dear = $this->objLanguage->languageText('word_dear');
    $thxmsg =  $this->objLanguage->languageText('mod_marketingrecruitmentforum_thxmsg1','marketingrecruitmentforum');
/*------------------------------------------------------------------------------------------------------------------------------*/
    /**
     *get Student details from db
     */
     $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
     $test  = $this->objstudcard->getstudInfo();
     
            
     $string  = $name . ' '. $surname;
     $str = $addy . '<br/>'.$postcode;
/*------------------------------------------------------------------------------------------------------------------------------*/
    /**
     *create a tabpane to display 
     */             
/*------------------------------------------------------------------------------------------------------------------------------*/    
    echo  $this->objMainheading->show() ;
    echo  '<br />'. '<br />'."<div align=\"center\">" . '<b>'.$str.'</b>'.   "</div>";
    echo  '<b>'.'<br />' . $dear  . ' ' . strtoupper($string).'</b>';
    echo  '<br />' .  '<br />' .  $thxmsg;
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah';
    echo  '<br />' .  '<br />'. 'blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah';
    echo  '<br />'  .'<br />';
    echo  '<b>'.'Regards,' . '</b>' . '<br />';
    echo  '<b>'.'The Marketing and Recruitment Support Forum'.'</b>';
    
?>
