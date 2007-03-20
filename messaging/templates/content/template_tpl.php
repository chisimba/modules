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

// add scriptaculous js libraries
$this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
$this->objScriptaculous->show();

// add messaging module js library
$headerParams = $this->getJavascriptFile('messaging.js', 'messaging');
$this->appendArrayVar('headerParams', $headerParams);

// select all checkbox js library
$headerParams = $this->getJavascriptFile('selectall.js', 'htmlelements');
$this->appendArrayVar('headerParams', $headerParams);

// sort table js library
$headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
$this->appendArrayVar('headerParams', $headerParams);

// set up layout
if($mode == 'iframe'){
    $this->setVar('pageSuppressBanner', TRUE);
    $this->setVar('pageSuppressContainer', TRUE);
    $this->setVar('pageSuppressSearch', TRUE);
    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('suppressFooter', TRUE);
    $this->setVar('bodyParams', 'onload="javascript:jsHideLoading();"');
}elseif($mode == 'popup'){
    // add x js library (cross browser library)
    $headerParams = $this->getJavascriptFile('x.js', 'htmlelements');
    $this->appendArrayVar('headerParams', $headerParams);

    $this->setVar('pageSuppressBanner', TRUE);
    $this->setVar('pageSuppressContainer', TRUE);
    $this->setVar('pageSuppressSearch', TRUE);
    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('suppressFooter', TRUE);
}elseif($mode == 'textroom'){
    $this->setVar('pageSuppressSearch', TRUE);
//    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('footerStr', '');
    $this->setLayoutTemplate('room_text_only_tpl.php');
    $this->setVar('bodyParams', 'onload="javascript:jsOnloadChat(\'\');" onunload="clearTimeout(chatTimer);"');
}elseif($mode == 'room'){
    $this->setVar('pageSuppressSearch', TRUE);
//    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('footerStr', '');
    $this->setLayoutTemplate('room_tpl.php');
    $this->setVar('bodyParams', 'onload="javascript:jsOnloadChat(\'\');" onunload="clearTimeout(chatTimer);clearTimeout(userTimer);"');
}else{
    $this->setLayoutTemplate('layout_tpl.php');
}

echo $templateContent;
?>