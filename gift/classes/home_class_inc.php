<?php

class home extends object{

    public function init()
    {
        $this->loadClass('htmlheading', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadclass('link','htmlelements');
    }

    public function getLeftContent($toSelect, $action){

        $addgift  = new link($this->uri(array("action"=>"add")));
        $editNonArchivedgift = new link($this->uri(array("action"=>"result","archived"=>"0")));
        $editArchivedgift = new link($this->uri(array("action"=>"result","archived"=>"1")));

        $addgift->link  = $this->objLanguage->languageText('mod_home_addLink','gift');
        $editNonArchivedgift->link = $this->objLanguage->languageText('mod_home_editNonArchivedLink','gift');
        $editArchivedgift->link = $this->objLanguage->languageText('mod_home_editArchivedLink','gift');

        $list=array(
            "0"=>$addgift->show(),
            "1"=>$editNonArchivedgift->show(),
            "2"=>$editArchivedgift->show()
        );

        $desc = '<ul id="nav-secondary">';
        $cssClass = '';
        foreach($list as $element){
             if(strtolower($element) == strtolower($toSelect)) {
                    $cssClass = ' class="active" ';
             }
             $desc.='<li '.$cssClass.'>'.$element.'</li>';
        }
        $desc.='</ul>';
        return $desc;
    }
}

?>
