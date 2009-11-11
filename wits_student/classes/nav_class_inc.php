<?php

class nav extends object {

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadclass('link','htmlelements');
        $this->objDbSysconfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->storyparser=$this->getObject('storyparser');
    }

    public function getLeftContent() {

        $sidecatid=$this->objDbSysconfig->getValue('SIDE_NAV_CATEGORY','wits_student');
        $sidenavs=$this->storyparser->getStoryByCategory($sidecatid);
        $list=array();

        foreach($sidenavs as $nav) {
            $sidelink = new link ($this->uri(array('action'=>'viewstory', 'storyid'=>$nav['id']),"wits_student"));
            $sidelink->link=   $nav['title'];
            $list[] = $sidelink->show();
        }

        $desc=
            '<ul id="nav-secondary">';
        $cssClass = '';
        foreach($list as $element) {
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
