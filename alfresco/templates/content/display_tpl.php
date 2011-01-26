<?php
/* -------------------- template for redmine module ----------------*/

// security check-must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
* Template for the redmine module
* @author Warren Windvogel
* @package redmine
*/

$this->setLayoutTemplate('layout_tpl.php');

echo $templateContent;

?>