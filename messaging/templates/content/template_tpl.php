<?php
/* -------------------- template for messaging module ----------------*/

// security check-must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
* @package messaging
*/

/**
* Template for the messaging module
* Author Kevin Cyster
* */

if(isset($script) && $script == TRUE){
    $this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
    $this->objScriptaculous->show();
}

// set up layout
if($mode == 'popup'){
    $this->setVar('pageSuppressContainer', TRUE);
    $this->setVar('pageSuppressSearch', TRUE);
    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('suppressFooter', TRUE);
}elseif($mode == 'textroom'){
    $this->setVar('pageSuppressSearch', TRUE);
    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('footerStr', '');
    $this->setLayoutTemplate('room_text_only_tpl.php');
}elseif($mode == 'room'){
    $this->setVar('pageSuppressSearch', TRUE);
    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('footerStr', '');
    $this->setLayoutTemplate('room_tpl.php');
}else{
    $this->setLayoutTemplate('layout_tpl.php');
}

echo $templateContent;
?>