<?php
    $this->loadclass('link','htmlelements');

    $mainjs = '<script src="'.$this->getResourceUri('js/main.js').'" type="text/javascript"></script>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/main.css').'"/>';

    $this->appendArrayVar('headerParams', $mainjs);
    $this->appendArrayVar('headerParams', $maincss);
    
    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);
    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id ="mainContent">Hello Document Management System</div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();
?>
