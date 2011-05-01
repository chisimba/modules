<?php
/* 
 * the Menu template for the report section of the academic module of SMIS
 *   @author charles mhoja
 *   @email charlesmdack@gmail.com
 */

////loading the helper classes

$this->loadClass('htmltable','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('link','htmlelements');
$this->loadClass('href','htmlelements');
$this->loadClass('csslayout','htmlelements');

function show_report_menus(){
///link for student results
$st_link=new link();
$st_link->type='http';
$st_link->link='Student Results';
$st_link->style='bgcolor=red';
$st_link->href='?module=tzschoolacademics&action=Report &action2=StudentResults';

///link for class results
$class_link=new link();
$class_link->type='http';
$class_link->link='Class Results';
$class_link->href='?module=tzschoolacademics&action=Report &action2=ClassResults';

///link for subject results
$subj_Link=new link();
$subj_Link->type='http';
$subj_Link->link='Subject results';
$subj_Link->href='?module=tzschoolacademics&action=Report &action2=SubjectResults';

///link for Failed students results
$st_fail=new link();
$st_fail->type='http';
$st_fail->link='Failured Student';
$st_fail->href='?module=tzschoolacademics &action=Report &action2=FailuredStudents';

///link for best student results
$best_student=new link();
$best_student->type='http';
$best_student->link='Best Students';
$best_student->href='?module=tzschoolacademics&action=Report &action2=BestStudents';

///link for student results
$st_report=new link();
$st_report->type='http';
$st_report->link='Student Report';
$st_report->href='?module=tzschoolacademics&action=Report &action2=StudentReport';

///link for student results
$st_list=new link();
$st_list->type='http';
$st_list->link='Student List';
$st_list->href='?module=tzschoolacademics&action=Report &action2=StudentList';


///creating navigation/links layout
$link_table=new htmlTable('report_nav');
$link_table->width='100%';
$link_table->border='0px';
$link_table->cellpadding='1px';
$link_table->cellspacing='1px';
$link_table->attributes='bgcolor=black';
$link_table->startRow();
$link_table->addCell($st_link->show(), $width='100%', $valign="middle", $align=center, $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

$link_table->startRow();
$link_table->addCell($class_link->show(), $width='100%', $valign="middle", $align=center, $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

$link_table->startRow();
$link_table->addCell($subj_Link->show(), $width='100%', $valign="middle", $align=center, $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

$link_table->startRow();
$link_table->addCell($st_fail->show(), $width='100%', $valign="middle", $align=center, $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

$link_table->startRow();
$link_table->addCell($best_student->show(), $width='100%', $valign="middle", $align=center, $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

$link_table->startRow();
$link_table->addCell($st_report->show(), $width='100%', $valign="middle", $align=center, $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

$link_table->startRow();
$link_table->addCell($st_list->show(), $width='100%', $valign="middle", $align=center, $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

return $link_table->show();
}

?>
