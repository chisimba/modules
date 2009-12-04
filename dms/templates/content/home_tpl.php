<?php
    $this->loadclass('link','htmlelements');

    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/adapter/ext/ext-base.js').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/ext-all.js').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('js/ext-3.0.0/resources/css/ext-all.css').'"/>';
    //$debugcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/debug.css').'"/>';
    //$debugjs = '<script src="'.$this->getResourceUri('js/debug.js').'" type="text/javascript"></script>';
    $mainjs = '<script src="'.$this->getResourceUri('js/main.js').'" type="text/javascript"></script>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/main.css').'"/>';
    $buttoncss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css').'"/>';
    $uploadsjs = '<script src="'.$this->getResourceUri('js/grouping.js').'" type="text/javascript"></script>';
    $columnnodecss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('js/ext-3.0.0/examples/ux/css/ColumnNodeUI.css').'"/>';
    $columnnodejs = '<script src="'.$this->getResourceUri('js/ext-3.0.0/examples/ux/ColumnNodeUI.js').'" type="text/javascript"></script>';
    //$extjs=$this->newObject('extjs','htmlelements');
    //$extjs->show();
    
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    //$this->appendArrayVar('headerParams', $debugcss);
    //$this->appendArrayVar('headerParams', $debugjs);
    $this->appendArrayVar('headerParams', $mainjs);
    $this->appendArrayVar('headerParams', $maincss);
    $this->appendArrayVar('headerParams', $buttoncss);
    $this->appendArrayVar('headerParams', $uploadsjs);
    $this->appendArrayVar('headerParams', $columnnodecss);
    $this->appendArrayVar('headerParams', $columnnodejs);

    $upload = "";
    if($this->getParam("upload")) {
        $upload = $this->getparam("upload");
    }

    //url
    $searchUrl = str_replace("amp;", "", $this->uri(array('action'=>'searchforfile')));
    $uploadUrl = str_replace("amp;", "", $this->uri(array('action'=>'uploadFile')));
    $JSONUrl = str_replace("amp;", "", $this->uri(array('action'=>'getJSONdata')));
    $adminUrl = str_replace("amp;", "", $this->uri(array('action'=>'admin')));

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);
    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id ="mainContent">';
    $content = '<div id="heading"><h1>'.$this->objUtils->showPageHeading().'</h1></div>';
    $content .= '<div id="buttons"></div>';
    $content .= '<div id="recent-uploads"></div>';
    $content .= '<div id="searchpane"></div>';
    $content .= '<input id="uploadURL" type="hidden" value="'.$uploadUrl.'">';
    $content .= '<input id="searchURL" type="hidden" value="'.$searchUrl.'">';
    $content .= '<input id="adminURL" type="hidden" value="'.$adminUrl.'">';
    $rightSideColumn .= $content;
    $rightSideColumn .= '</div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();
    $mainjs = "<script type='text/javascript'>
        Ext.onReady(function() {

            Ext.QuickTips.init();";

    $mainjs .= $this->objUtils->getRecentFiles($JSONUrl);
    $mainjs .= "
                });";
    $mainjs .= "</script>";


    echo $mainjs;
    //echo "<a href=\"#\" onclick=\"Ext.log('Hello from the Ext console. This is logged using the Ext.log function.');return false;\">Click Here For Debug</a>";
?>
