<?php

class home extends object{

    /**
     * Initialises the classes to be used
     */
    public function init()
    {
        $this->loadClass('htmlheading', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadclass('link','htmlelements');
    }

    /**
     * Links are displayed on the left hand side of every page.  This method
     * gets that list.
     * @param string $toSelect
     * @param string $action
     * @return string
     */
    public function getLeftContent($toSelect, $action){

        $homelink = new link($this->uri(NULL));
        $addgift  = new link($this->uri(array("action"=>"add")));
        $editgift = new link($this->uri(array("action"=>"result")));

        $homelink->link = $this->objLanguage->languageText('mod_home_homeLink','gift');;
        $addgift->link  = $this->objLanguage->languageText('mod_home_addLink','gift');
        $editgift->link = $this->objLanguage->languageText('mod_home_editLink','gift');

        $list=array(
            "0"=>$homelink->show(),
            "1"=>$addgift->show(),
            "2"=>$editgift->show()
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

    public function homePage() {
        $heading = new htmlheading($this->objLanguage->languageText('mod_homeWelcome_heading','gift'),1);
        $body    = $this->objLanguage->languageText('mod_homeWelcome_body','gift');
        $notice  = $this->objLanguage->languageText('mod_homeWelcome_warning','gift');
        return $heading->show()."<br>".$body."<br>".$notice;
    }
}

?>
