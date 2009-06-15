<?php

class nav extends object{

    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadclass('link','htmlelements');
    }

    public function getLeftContent(){
        $sectionA = new link ($this->uri(array('action'=>'overview'),"ads"));
        $sectionA->link=   $this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');

        $sectionB = new link ($this->uri(array('action'=>'rules_and_syllabus'),"ads"));
        $sectionB->link=   $this->objLanguage->languageText('mod_ads_section_b_rules_and_syllabus', 'ads');

        $sectionC = new link ($this->uri(array('action'=>'subsidy_requirements'),"ads"));
        $sectionC->link=   $this->objLanguage->languageText('mod_ads_section_c_subsidy_requirements', 'ads');

        $sectionD = new link ($this->uri(array('action'=>'outcomes'),"ads"));
        $sectionD->link=   $this->objLanguage->languageText('mod_ads_section_d_outcomes', 'ads');

        $sectionE = new link ($this->uri(array('action'=>'resources'),"ads"));
        $sectionE->link=   $this->objLanguage->languageText('mod_ads_section_e_resources', 'ads');

        $sectionF = new link ($this->uri(array('action'=>'collab'),"ads"));
        $sectionF->link=   $this->objLanguage->languageText('mod_ads_section_f_collab', 'ads');

        $sectionG = new link ($this->uri(array('action'=>'review'),"ads"));
        $sectionG->link=   $this->objLanguage->languageText('mod_ads_section_g_review', 'ads');

        $sectionH = new link ($this->uri(array('action'=>'contact'),"ads"));
        $sectionH->link=   $this->objLanguage->languageText('mod_ads_section_h_contact', 'ads');

        $desc=
'<ul>
<li>'.$sectionA->show().'</li>
<li>'.$sectionB->show().'</li>
<li>'.$sectionC->show().'</li>
<li>'.$sectionD->show().'</li>
<li>'.$sectionE->show().'</li>
<li>'.$sectionF->show().'</li>
<li>'.$sectionG->show().'</li>
<li>'.$sectionH->show().'</li>
</ul>';
        return $desc;
    }
}

?>
