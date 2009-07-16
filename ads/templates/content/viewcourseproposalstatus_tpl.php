<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/ext-base.js').'" type="text/javascript"></script>';
    $extall = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('scripts/ext-all.css').'"/>';
    $extalldebug = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/ext-all-debug.js').'" type="text/javascript"></script>';
    $mainjs = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/check-radio.js').'" type="text/javascript"></script>';
    $styleSheet="
    <style type=\"text/css\">
        .x-check-group-alt {
            background: #D1DDEF;
            border-top:1px dotted #B5B8C8;
            border-bottom:1px dotted #B5B8C8;
        }
        div#myForm {
            padding: 0 3em;
        }
    </style>
    ";
    
    //append to the top of the page
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extall);
    $this->appendArrayVar('headerParams', $extalldebug);
    $this->appendArrayVar('headerParams', $mainjs);
    $this->appendArrayVar('headerParams', $styleSheet);
    
    // display the extj radio form
    echo '<div id="myForm">';
    echo '<div id="form-ct"></div></div>';
    echo '<input type="hidden" name="id" id="id" value="'.$this->getParam("id").'">';
?>
