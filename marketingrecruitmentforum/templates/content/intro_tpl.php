<?php
/**
 *create a template explaining the purpose of the module an displaying the introduction page
 */
 
 
 /**
  *create form heading
  */
  
 $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
 $this->objMainheading->type=1;
 $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_introduction2','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/

 /**
  *create all form language elements 
  */     
 $about = $this->objLanguage->languageText('mod_marketingrecruitmentforum_about','marketingrecruitmentforum');
 $datacapturing = $this->objLanguage->languageText('mod_marketingrecruitmentforum_datacapturing','marketingrecruitmentforum');
 $datainfo1 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_datainfo22','marketingrecruitmentforum') . '<br />';
 $datainfo2 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_datainfo2','marketingrecruitmentforum');
 $datainfo3 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_datainfo3','marketingrecruitmentforum') . '<br />';
 
 $search  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchfacilities','marketingrecruitmentforum');
 $searchinfo  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinfo','marketingrecruitmentforum');
 $search1 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search101','marketingrecruitmentforum');
 $search2 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search201','marketingrecruitmentforum');
 $search3 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search301','marketingrecruitmentforum');
 
 $searchinfo1  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinfo110','marketingrecruitmentforum');
 $search4 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search401','marketingrecruitmentforum');
 $search5 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search501','marketingrecruitmentforum');

/*------------------------------------------------------------------------------*/ 
   
 $string1  = $about;
 $stringdata  =  '<br />'  .$datacapturing . '<br />' .$datainfo1 . '<br />' .$datainfo2 . '<br />' .$datainfo3;
 $searchstring = '<br />'. $searchinfo .'<br />'. '<br />'.  $search1 .'<br />'. $search2 .'<br />'.'<br />'.$search3;
 $searchstring1 = '<br />'. $searchinfo1 .'<br />'.'<br />'. $search4 .'<br />'. $search5;// .'<br />'. '*'.$search3;
 
/*------------------------------------------------------------------------------*/
 /**
  *display data to screen
  */
      
 echo $this->objMainheading->show() . '<br />';
 echo $string1;
 echo $stringdata;
 echo '<br />' . $search;
 echo $searchstring . '<br />' . $searchstring1;
?>
