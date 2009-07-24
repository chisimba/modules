<?php

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
    $content = '<div id="myForm">';
    $content .= '<div id="form-ct"></div></div>';
    $content .= '<input type="hidden" name="id" id="id" value="'.$this->getParam("id").'">';

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    //Add the table to the centered layer
    $rightSideColumn = $content;
    // Add Right Column
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    //Output the content to the page
    echo $cssLayout->show();
?>
