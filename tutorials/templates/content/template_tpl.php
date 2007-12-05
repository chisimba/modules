<?php
/* -------------------- template for tutorials module ----------------*/

// security check-must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
* @package tutorials
*/

/**
* Template for the tutorials module
* Author Kevin Cyster
* */

$this->setLayoutTemplate('layout_tpl.php');

echo $templateContent;
?>