<?php

/*
 * The contents of this file are subject to the Meeting Manager Public license you may not use or change this file except in
 * compliance with the License. You may obtain a copy of the License by emailing this address udsmmeetingmanager@googlegroups.com
 *  @author victor katemana
 *  @email princevickatg@gmail.com


 */

class upload_marks extends object {

      public $db_access;
    
    public function init() {

      $this->db_access = $this->newObject('marks_db ', 'tzschoolacademics');
    }

    /*
      below function instantiates the form elements of the htmlelements class
     */

    public function loadElements() {

        //Load the form class
        $this->loadClass('form', 'htmlelements');
        //Load the textinput class
        $this->loadClass('textinput', 'htmlelements');
        //Load the textarea class
        //Load the label class
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        //Load the button object
        $this->loadClass('button', 'htmlelements');

        //load the dropdown class

        $this->loadClass('dropdown','htmlelements');
    }


    public  function buildUploadForm() {
        
     $this->loadElements();
     
     $objform = new form('uploadmarks');
     $objform->setEncType($encType = 'multipart/form-data');
  //-----------------------------------------------------------------------------------
     $objlabel = new label('class');
     $objdropdown = new dropdown('class');
     $displayclass =  $this->db_access->load_classes();
     foreach ($displayclass as  $row )
     {
      $objdropdown->addOption($value=$row['puid'],$label=$row['class_name'].$row['stream']);
     }
 //---------------------------------------------------------------------------------------------
     $objsubjlabel = new label('subject');
     $objsubjdropdown = new dropdown('subject');
     $displaysubject = $this->db_access->load_subjects();

      foreach ($displaysubject as $row)
      {
       $objsubjdropdown->addOption($value=$row['puid'],$label=$row['subject_name']);
      }
 //-------------------------------------------------------------------------------------------------
      $objacalabel = new label('academic year');
      $objacadropdown = new dropdown('academic_year');
      $displayacademic_year = $this->db_access->load_academic_year();
      foreach ($displayacademic_year as $row)
      {
      $objacadropdown->addOption($row['puid'], $row['year_name']);
      }
  //------------------------------------------------------------------------------------------------
      $objtermlabel = new label('term');
      $objtermdropdown = new dropdown('term');
      $displayterm = $this->db_access->load_term ();

      foreach ($displayterm as $row)
      {
      $objtermdropdown->addOption($row['puid'], $row['term_name']);
      }
  //------------------------------------------------------------------------------------------------------
      $objexamtypelabel = new label('Exam');

      $objexamtypedropdown = new dropdown('exam');
      $displayexam = $this->db_access->load_term();

      foreach ($displayexam as $row)
      {
       $objtermdropdown->addOption($row['puid'], $row['exam_type']);
      }
  //------------------------------------------------------------------------------------------------------------
      $objform->addToForm($objlabel->show());
      $objform->addToForm( $objsubjdropdown->show());
   
      $objform->addToForm( $objsubjlabel->show());
      $objform->addToForm(  $objsubjdropdown->show());
          
       $objform->addToForm(  $objacalabel->show());
        $objform->addToForm(  $objacadropdown->show());

         $objform->addToForm(   $objtermlabel->show());
          $objform->addToForm(  $objtermdropdown->show());

           $objform->addToForm(   $objexamtypelabel->show());
            $objform->addToForm(   $objexamtypedropdown->show());
   

     
   return $objform->show();


    }


}

?>
