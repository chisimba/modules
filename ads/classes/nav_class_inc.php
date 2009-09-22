<?php

class nav extends object{

    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadclass('link','htmlelements');
    }

    public function getLeftContent($toSelect, $action, $courseid, $edit = "YES"){
        if ($action == 'viewform' || $action == 'submitform') {
          $action = 'viewform';
        }

        $home = new link ($this->uri(array('action'=>'home')));
        $home->link= $this->objLanguage->languageText('mod_ads_home', 'ads');

        $summary =new link($this->uri(array('action'=>'showcourseprophist','courseid'=>$courseid,'selectedtab'=>'0')));
        $summary->link="Summary";


        $sectionA = new link ($this->uri(array('action'=>$action, 'formnumber'=>'A', 'courseid'=>$courseid),"ads"));
        $sectionA->link=   $this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');

        $sectionB = new link ($this->uri(array('action'=>$action, 'formnumber'=>'B', 'courseid'=>$courseid),"ads"));
        $sectionB->link=   $this->objLanguage->languageText('mod_ads_section_b_rules_and_syllabus', 'ads');
        
        $sectionC = new link ($this->uri(array('action'=>$action, 'formnumber'=>'C', 'courseid'=>$courseid),"ads"));
        $sectionC->link=   $this->objLanguage->languageText('mod_ads_section_c_subsidy_requirements', 'ads');

        $sectionD = new link ($this->uri(array('action'=>$action, 'formnumber'=>'D', 'courseid'=>$courseid),"ads"));
        $sectionD->link=   $this->objLanguage->languageText('mod_ads_section_d_outcomes', 'ads');

        $sectionE = new link ($this->uri(array('action'=>$action, 'formnumber'=>'E', 'courseid'=>$courseid),"ads"));
        $sectionE->link=   $this->objLanguage->languageText('mod_ads_section_e_resources', 'ads');

        $sectionF = new link ($this->uri(array('action'=>$action, 'formnumber'=>'F', 'courseid'=>$courseid),"ads"));
        $sectionF->link=   $this->objLanguage->languageText('mod_ads_section_f_collab', 'ads');

        $sectionG = new link ($this->uri(array('action'=>$action, 'formnumber'=>'G', 'courseid'=>$courseid),"ads"));
        $sectionG->link=   $this->objLanguage->languageText('mod_ads_section_g_review', 'ads');

        $sectionH = new link ($this->uri(array('action'=>$action, 'formnumber'=>'H', 'courseid'=>$courseid),"ads"));
        $sectionH->link=   $this->objLanguage->languageText('mod_ads_section_h_contact', 'ads');
/*
        $list=array(
            "0"=>$sectionA->show(),
            "1"=>$sectionB->show(),
            "2"=>$sectionC->show(),
            "3"=>$sectionD->show(),
            "4"=>$sectionE->show(),
            "5"=>$sectionF->show(),
            "6"=>$sectionG->show(),
            "7"=>$sectionH->show()
        );*/
        $list=array(
            "0"=>$home->show(),
            "1"=>$summary->show(),
            "2"=>$sectionA->show(),
            "3"=>$sectionB->show(),
            "4"=>$sectionC->show(),
            "5"=>$sectionD->show(),
            "6"=>$sectionE->show(),
            "7"=>$sectionF->show(),
            "8"=>$sectionG->show(),
            "9"=>$sectionH->show()
        );
        $desc=
        '<ul id="nav-secondary">';
        $cssClass = '';
        foreach($list as $element){
             if(strtolower($element) == strtolower($toSelect)) {
                    $cssClass = ' class="active" ';
             }
            $desc.='<li $cssClass>'.$element.'</li>';
        }
        $desc.='</ul>';
        return $desc;
    }
}

?>
