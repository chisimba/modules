<?php
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/adapter/ext/ext-base.js').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/ext-all.js').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('js/ext-3.0.0/resources/css/ext-all.css').'"/>';
    $config = '<script language="JavaScript" src="'.$this->getResourceUri('js/config.js').'" type="text/javascript"></script>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/main.css').'"/>';

    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $config);
    $this->appendArrayVar('headerParams', $maincss);

    $typeUrl = str_replace("amp;", "", $this->uri(array('action'=>'savefiletype')));;

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);
    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id ="mainContent">';
    $content = '<div id="heading"><h1>'.$this->objUtils->showPageHeading($action).'</h1></div>';
    $content .= '<div id="buttons"></div>';
    $content .= '<div id="config"></div>';
    $content .= '<div id="addtype-win" class="x-hidden"><div class="x-window-header">Hello Dialog</div></div>';
    $content .= '<input id="typeURL" type="hidden" value="'.$typeUrl.'">';
    $rightSideColumn .= $content;
    $rightSideColumn .= '</div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();

    $mainjs = "<script type='text/javascript'>
        Ext.onReady(function() {

            Ext.QuickTips.init();";

    $mainjs .= "
                });";
    $mainjs .= "</script>";
    
    //echo $mainjs;
?>
