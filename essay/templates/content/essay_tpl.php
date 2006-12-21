<?php
/*
* Template for main essay management page.
* @package essay
*/

/**************** Set Layout template ***************************/
$this->setLayoutTemplate('essay_layout_tpl.php');

$this->loadclass('htmltable','htmlelements'); 

/**************** Display Page data *****************************/
echo $list;
?>