<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This form will save the registration of new student
 * and also for editing the details of the registered students
 *
 * @author Boniface Chacha <bonifacechacha@gmail.com>
 */
class studentform extends object{
   public $lang;
   private $fNameValue='';
   private $lNameValue='';
   private $oNameValue='';
   private $dobValue='2011-08-01';
   private $genderValue='M';
   private $religionValue='';

   public function init(){
       $this->lang=$this->getObject('language', 'language');
    }

    private function load(){
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('datepicker', 'htmlelements');
    }

    private function build(){
        $this->load();
        
        $form=new form('Student',  $this->getAction());

        $fNameLabel=new label($this->lang->languageText('mod_tzschoolacademics_fname_label','tzschoolacademics'),'firstname');
        $fNameField=new textinput('firstname');
        $form->addToForm($fNameLabel->show());
        $fNameField->setValue($this->fNameValue);
        $form->addToForm($fNameField->show());

        $lNameLabel=new label($this->lang->languageText('mod_tzschoolacademics_lname_label','tzschoolacademics'),'lastname');
        $lNameField=new textinput('lastname');
        $form->addToForm($lNameLabel->show());
        $lNameField->setValue($this->lNameValue);
        $form->addToForm($fNameField->show());

        $oNameLabel=new label($this->lang->languageText('mod_tzschoolacademics_oname_label','tzschoolacademics'),'othernames');
        $oNameField=new textinput('othernames');
        $form->addToForm($oNameLabel->show());
        $oNameField->setValue($this->oNameValue);
        $form->addToForm($oNameField->show().'<br>');

        $genderLabel=new label($this->lang->languageText('mod_tzschoolacademics_gender_label','tzschoolacademics'),'gender');
        $genderField=new dropdown('gender');
        $form->addToForm($genderLabel->show().'<br>');
        $genderField->addOption('M','MALE');
        $genderField->addOption('F','FEMALE');
        
        $genderField->setValue($this->genderValue);
        $form->addToForm($genderField->show().'<br>');

        $dobLabel=new label($this->lang->languageText('mod_tzschoolacademics_dob_label','tzschoolacademics'),'dob');
        $dobField=$this->getObject('datepicker', 'htmlelements');
        $form->addToForm($dobLabel->show().'<br>');
       
        $dobField->setDefaultDate($this->dobValue);
        $form->addToForm($dobField->show().'<br>');

        $religionLabel=new label($this->lang->languageText('mod_tzschoolacademics_religion_label','tzschoolacademics'),'religion');
        $religionField=new textinput('religion');
        $form->addToForm($religionLabel->show().'<br>');
        $religionField->setValue($this->religionValue);
        $form->addToForm($religionField->show().'<br>');
        echo $form->show();
    }

        private function getAction(){
        $action=$this->getParam('action','edit');
        if($action=='edit')
            $formAction=  $this->uri(array('action'=>'edit'),'lean');

        else
            $formAction=$this->uri (array('action'=>'add'),'lean');
        return $formAction;
    }

        public function show(){
        $this->build();
    }

}
?>
