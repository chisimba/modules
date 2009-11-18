<?php
    $this->loadclass('link','htmlelements');

    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/adapter/ext/ext-base.js').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/ext-all.js').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('js/ext-3.0.0/resources/css/ext-all.css').'"/>';
    $searchjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/examples/ux/SearchField.js').'" type="text/javascript"></script>';
    $mainjs = '<script src="'.$this->getResourceUri('js/main.js').'" type="text/javascript"></script>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/main.css').'"/>';
    $buttoncss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css').'"/>';
    
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $searchjs);
    $this->appendArrayVar('headerParams', $mainjs);
    $this->appendArrayVar('headerParams', $maincss);
    $this->appendArrayVar('headerParams', $buttoncss);

    $upload = "";
    if($this->getParam("upload")) {
        $upload = $this->getparam("upload");
    }

    //url
    $searchUrl = str_replace("amp;", "", $this->uri(array('action'=>'searchforfile')));
    $uploadUrl = str_replace("amp;", "", $this->uri(array('action'=>'uploadFile')));

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);
    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id ="mainContent">';
    $content = '<div id="heading"><h1>'.$this->objUtils->showPageHeading().'</h1></div>';
    $content .= '<div id="buttons"></div>';
    $content .= '<div id="searchpane"></div>';
    $content .= '<div id="recentfiles">'.$this->objUtils->getRecentFiles().'</div>';
    $content .= '<input id="uploadURL" type="hidden" value="'.$uploadUrl.'">';
    $rightSideColumn .= $content;
    $rightSideColumn .= '</div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();
    $mainjs = '<script type="text/javascript">
        Ext.onReady(function() {';

    $mainjs .= $this->objUtils->searchFiles($searchUrl);
    $mainjs .= '
                });';
    $mainjs .= '</script>';


    echo $mainjs;
    //$fileuploadjs = $this->objUtils->showUploadForm();
    //echo $fileuploadjs;
?>
