<?php

    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    $mainjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/check-radio.js').'" type="text/javascript"></script>';
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
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
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
