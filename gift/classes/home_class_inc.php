<?php

class home extends object{

    /**
     * Initialises the classes to be used
     */
    public function init()
    {
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
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
        $addgift  = new link($this->uri(array("action"=>"addx")));
        $editgift = new link($this->uri(array("action"=>"resultx")));

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

    /**
     * Gets the layout of the home page and informs the user of what they
     * can do with this system.
     * @return string
     */
    public function homePage() {
        $form = new form('homeForm');
        $table = new htmltable();
        $heading = new htmlheading($this->objLanguage->languageText('mod_homeWelcome_heading','gift'),1);
        $body    = $this->objLanguage->languageText('mod_homeWelcome_body','gift');
        $notice  = $this->objLanguage->languageText('mod_homeWelcome_warning','gift');

        $viewButton = new button('view',$this->objLanguage->languageText('mod_home_editLink','gift'));
        $viewButton->setOnClick("window.location='".$this->uri(array('action'=>'result'))."';");

        $addButton = new button('add',$this->objLanguage->languageText('mod_home_addLink','gift'));
        $addButton->setOnClick("window.location='".$this->uri(array('action'=>'add'))."';");

        $table->startRow();
        $table->addCell($addButton->show());
        $table->addCell($viewButton->show());
        $table->endRow();

        $form->addToForm($heading->show()."<br>");
        $form->addToForm($body."<br>");
        $form->addToForm($notice."<br><br><br>");
        $form->addToForm($table->show());

        return $form->show();
    }
}

?>
