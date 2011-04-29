<?php
/* 
 * form to get student reg# 
 *   @author charles mhoja
 *   @email charlesmdack@gmail.com
 */

$this->loadClass('form','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('fieldset','htmlelements');
$this->loadClass('href','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('label','htmlelements');

///loading the academic report class
$this->loadClass('academic_report_class','tzschoolacademics');

////form starts here
$student_result_form=new form('student_result');
$student_result_form->action=$this->uri(array('action' =>'std_result'),'academic');  // form action
$student_result_form->displayType('2');

///creating a fieldset for the form
//$form_fielset=new fieldset();
//$student_result_form->beginFieldset($legend='Form to get Student result');
//$student_result_form->endFieldset();

$dropdown_obj=new dropdown('academic_yr');
$dropdown_label=new label('Academic year');



$textobj=new textinput('');
$textinput_label=new label('Enter Student Registration number');
$textobj->setName('st_regno');
$student_result_form->addToForm($textinput_label->show().$textobj->show());





?>

