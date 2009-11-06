<?php
    $this->loadclass('link','htmlelements');

    // scripts
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/config.css').'"/>';
    $mainjs = '<script src="'.$this->getResourceUri('js/config.js').'" type="text/javascript"></script>';

    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $maincss);
    $this->appendArrayVar('headerParams', $maincss);
    $this->appendArrayVar('headerParams', $mainjs);

    $emailNotification = 'false';
    $checkBoxUrl = $this->uri(array('action'=>'saveEmailConfig'));

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);
    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id ="config">';    
    $rightSideColumn .= '</div>';
    //$rightSideColumn .=$content.$note;
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    $myScript = '<script type="text/javascript">';
    $myScript .= '
        Ext.onReady(function(){
            Ext.QuickTips.init();

            showCheckBoxes("'.$emailNotification.'", "'.str_replace("amp;", "", $checkBoxUrl).'");
        });
    ';
    $myScript .= '</script>';

    echo $cssLayout->show();
    echo $myScript;
?>
