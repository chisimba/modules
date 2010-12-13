<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home_class_inc
 *
 * @author palesa
 */
class home extends object {

    public function init() {

        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadclass('link','htmlelements');
    }

    public function homePage() {
        $form = new form('newCourseForm');
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
