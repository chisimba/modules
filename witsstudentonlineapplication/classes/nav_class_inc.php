<?php

class nav extends object{

    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadclass('link','htmlelements');
    }

    public function getLeftContent($toSelect, $action, $courseid, $editable){
        if ($action == 'viewform' || $action == 'submitform') {
          $action = 'viewform';
        }

        $home = new link ($this->uri(array('action'=>'home')));
        $homeIcon='<img src="'.$this->getResourceUri('imgs/house.png').'">';
        $home->link='<font color="green">'.$homeIcon.' Home</font>';

        $generalinformation =new link($this->uri(array('action'=>'showgeneralinformationform')));
        $generalinformationimg='<img src="'.$this->getResourceUri('imgs/user.png').'"> General Information';
        $generalinformation->link ='<font color="green">'. $generalinformationimg.'</font>';
        
        $personaldetails =new link($this->uri(array('action'=>'showpersonaldetailsform')));
        $personaldetailsimg='<img src="'.$this->getResourceUri('imgs/user.png').'"> Personal Details';
        $personaldetails->link ='<font color="green">'. $personaldetailsimg.'</font>';
        
        $contactdetails =new link($this->uri(array('action'=>'showcontactdetailsform')));
        $contactdetailsimg='<img src="'.$this->getResourceUri('imgs/telephone.png').'"> Contact Details';
        $contactdetails->link ='<font color="green">'. $contactdetailsimg.'</font>';
        
        $contactdetails2 =new link($this->uri(array('action'=>'showcontactdetails2form')));
        $contactdetails2img='<img src="'.$this->getResourceUri('imgs/application.png').'"> Address Details';
        $contactdetails2->link ='<font color="green">'. $contactdetails2img.'</font>';
        
        $nextofkindetails =new link($this->uri(array('action'=>'shownextofkindetailsform')));
        $nextofkindetailsimg='<img src="'.$this->getResourceUri('imgs/group.png').'"> Next of kin';
        $nextofkindetails->link ='<font color="green">'. $nextofkindetailsimg.'</font>';

        $list=array(
            "0"=>$home->show(),
            "1"=>$generalinformation->show(),
            "2"=>$personaldetails->show(),
            "3"=>$contactdetails->show(),
            "4"=>$contactdetails2->show(),
            "5"=>$nextofkindetails->show()
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
