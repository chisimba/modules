<?php
/* 
 * Home of the report section of the academic module
 *   @author charles mhoja
 *   @email charlesmdack@gmail.com
 *   copyright udsm/ucc-Mip 2011
 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
die("You cannot view this page directly");
}

include_once 'report_menu_tpl.php';
$this->loadClass('csslayout','htmlelements');
///creating the layout of the page
$layoutObj=$this->newObject('csslayout', 'htmlelements');;
$layoutObj->setNumColumns(2);

 $report_links=show_report_menus();
//$layoutObj->setLeftColumnContent($link_table->show()); ///before
$layoutObj->setLeftColumnContent($report_links);  ///adding menus to the left content the layout


$layoutObj->setMiddleColumnContent($content);
echo $layoutObj->show();


?>
