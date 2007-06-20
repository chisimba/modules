<?php
/**
* Template to display the registration page
* @access public
*/

if(isset($suppressLayout) && $suppressLayout){
    $this->setVar('pageSuppressBanner', TRUE);
    $this->setVar('pageSuppressToolbar', TRUE);
    $this->setVar('suppressFooter', TRUE);
}else{
    $this->setLayoutTemplate('hivaids_layout_tpl.php');
}

echo $display.'<br />';

?>