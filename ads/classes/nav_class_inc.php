<?php

class nav extends object{

    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadclass('link','htmlelements');
    }

    public function getLeftContent($toSelect){
        $sectionA = new link ($this->uri(array('action'=>'overview'),"ads"));
        $sectionA->link=   $this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');

        $sectionB = new link ($this->uri(array('action'=>'rulesandsyllabus'),"ads"));
        $sectionB->link=   $this->objLanguage->languageText('mod_ads_section_b_rules_and_syllabus', 'ads');
//array("action"=>"viewform", "formnumber"=>"C", "courseid"=>"math")
        $sectionC = new link ($this->uri(array("action"=>"editform", "formnumber"=>"C", "courseid"=>"math"),"ads"));
        $sectionC->link=   $this->objLanguage->languageText('mod_ads_section_c_subsidy_requirements', 'ads');

        $sectionD = new link ($this->uri(array("action"=>"editform", "formnumber"=>"D", "courseid"=>"math"),"ads"));
        $sectionD->link=   $this->objLanguage->languageText('mod_ads_section_d_outcomes', 'ads');

        $sectionE = new link ($this->uri(array('action'=>'sectione'),"ads"));
        $sectionE->link=   $this->objLanguage->languageText('mod_ads_section_e_resources', 'ads');

        $sectionF = new link ($this->uri(array('action'=>'sectionf'),"ads"));
        $sectionF->link=   $this->objLanguage->languageText('mod_ads_section_f_collab', 'ads');

        $sectionG = new link ($this->uri(array('action'=>'review'),"ads"));
        $sectionG->link=   $this->objLanguage->languageText('mod_ads_section_g_review', 'ads');

        $sectionH = new link ($this->uri(array('action'=>'viewcontacts','coursenum'=>'0'),"ads"));
        $sectionH->link=   $this->objLanguage->languageText('mod_ads_section_h_contact', 'ads');

        $list=array(
            "0"=>$sectionA->show(),
            "1"=>$sectionB->show(),
            "2"=>$sectionC->show(),
            "3"=>$sectionD->show(),
            "4"=>$sectionE->show(),
            "5"=>$sectionF->show(),
            "6"=>$sectionG->show(),
            "7"=>$sectionH->show()
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
