<?php
/**
 *create a template explaining the purpose of the module
 */
 
 
 /**
  *create form heading
  */
 $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
 $this->objMainheading->type=1;
 $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_introduction','marketingrecruitmentforum');
 
 $about = $this->objLanguage->languageText('mod_marketingrecruitmentforum_about','marketingrecruitmentforum');
 $datacapturing = $this->objLanguage->languageText('mod_marketingrecruitmentforum_datacapturing','marketingrecruitmentforum');
 $datainfo1 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_datainfo1','marketingrecruitmentforum');
 $datainfo2 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_datainfo2','marketingrecruitmentforum');
 $datainfo3 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_datainfo3','marketingrecruitmentforum');
 
 $search  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchfacilities','marketingrecruitmentforum');
 $searchinfo  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinfo','marketingrecruitmentforum');
 $search1 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search1','marketingrecruitmentforum');
 $search2 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search2','marketingrecruitmentforum');
 $search3 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search3','marketingrecruitmentforum');
 
 $searchinfo1  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_searchinfo1','marketingrecruitmentforum');
 $search4 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search4','marketingrecruitmentforum');
 $search5 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_search5','marketingrecruitmentforum');
 
 $reportheading = $this->objLanguage->languageText('mod_marketingrecruitmentforum_reports','marketingrecruitmentforum');
 $reportinfo  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_reportinfo','marketingrecruitmentforum');
 
 $postings  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_postings','marketingrecruitmentforum');
 $postinginfo = $this->objLanguage->languageText('mod_marketingrecruitmentforum_postinginfo','marketingrecruitmentforum');
 
 
 
 
 $string1  = $about . '<br />';
 $stringdata  =  '<br />'  .$datacapturing . '<br />' . '*'.$datainfo1 . '<br />' . '*'.$datainfo2 . '<br />' . '*'.$datainfo3;
 $searchstring = '<br />'. $searchinfo .'<br />'. '*'. $search1 .'<br />'.'*'. $search2 .'<br />'. '*'.$search3;
 $searchstring1 = '<br />'  . '<br />'. $searchinfo1 .'<br />'. '*'. $search4 .'<br />'.'*'. $search5;// .'<br />'. '*'.$search3;
 $reportstring  = '<br />'  . '<br />'. $reportheading .'<br />'. '*'. $reportinfo;
 $postingstring = '<br />'  . '<br />'. $postings .'<br />'. '*'. $postinginfo;

 //  $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
 //  $values  = $this->objschoolname->readpostcodes();
 
 
 echo $this->objMainheading->show();
 echo $string1;
 echo $stringdata;
 echo '<br />' . '<br />' . $search;  //check why does not print
 echo $searchstring . '<br />' . $searchstring1;
 echo $reportstring;
 echo $postingstring;

?>
