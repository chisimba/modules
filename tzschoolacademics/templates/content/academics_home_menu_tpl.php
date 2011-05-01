<?php
/* 
 * The menu template for the academic module home page
 *
 *  @author charles mhoja
 *   @email charlesmdack@gmail.com
 */

$this->loadClass('htmltable','htmlelements');
$this->loadClass('link','htmlelements');
$this->loadClass('href','htmlelements');

function show_academic_home_menus(){
///Admission menu
$adm_link=new link();
$adm_link->type='http';
$adm_link->link='Student Admission';
$adm_link->style='bgcolor=red';
$adm_link->href='?module=tzschoolacademics&action=Admission';

///Result menu
$result_link=new link();
$result_link->type='http';
$result_link->link='Results';
$result_link->href='?module=tzschoolacademics&action=Results';

///Report Menu
$report_Link=new link();
$report_Link->type='http';
$report_Link->link='Report';
$report_Link->href='?module=tzschoolacademics&action=Report';



///creating navigation/links layout
$link_table=new htmlTable('Academic_home_nav');
$link_table->width='100%';
$link_table->border='0px';
$link_table->cellpadding='1px';
$link_table->cellspacing='1px';
$link_table->attributes='bgcolor=black';
$link_table->startRow();
$link_table->addCell($adm_link->show(), $width='100%', $valign="middle", $align='center', $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

$link_table->startRow();
$link_table->addCell($result_link->show(), $width='100%', $valign="middle", $align='center', $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

$link_table->startRow();
$link_table->addCell($report_Link->show(), $width='100%', $valign="middle", $align='center', $class=null, $attrib='bgcolor=white',$border = '4px');
$link_table->endRow();

return $link_table->show();
}

?>

