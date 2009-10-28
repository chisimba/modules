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
        $home->link='<font color="green">'.$homeIcon.'Home</font>';

        $contactdetails =new link($this->uri(array('action'=>'showcontactdetailsform')));
        $contactdetailsimg='<img src="'.$this->getResourceUri('imgs/telephone.png').'"> Contact details';
        $contactdetails->link ='<font color="green">'. $contactdetailsimg.'</font>';

        $nextofkindetails =new link($this->uri(array('action'=>'shownextofkindetailsform')));
        $nextofkindetailsimg='<img src="'.$this->getResourceUri('imgs/group.png').'"> Next of kin';
        $nextofkindetails->link ='<font color="green">'. $nextofkindetailsimg.'</font>';

        $list=array(
            "0"=>$home->show(),
            "1"=>$contactdetails->show(),
            "2"=>$nextofkindetails->show()
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
