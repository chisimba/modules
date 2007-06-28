<?php
/* -------------------- template for wiki version 2 module ----------------*/

// security check-must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
* @package wiki version 2
*/

/**
* Template for the wiki version 2 module
* Author Kevin Cyster
* */

if(isset($popup)){
    $this->setVar('pageSuppressBanner', TRUE);
    $this->setVar('pageSuppressContainer', TRUE);
    $this->setVar('pageSuppressSearch', TRUE);
    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('suppressFooter', TRUE);    
}elseif(isset($iframe)){
    $this->setVar('pageSuppressBanner', TRUE);
    $this->setVar('pageSuppressContainer', TRUE);
    $this->setVar('pageSuppressSearch', TRUE);
    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('suppressFooter', TRUE);    
}else{
    $this->setLayoutTemplate('layout_tpl.php');
}
$this->setVar('pageSuppressXML', TRUE);
echo $templateContent;
?>