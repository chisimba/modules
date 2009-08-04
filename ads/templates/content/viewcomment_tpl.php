<?php

    // security check - must be included in all scripts
    if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }
    // end security check

    $this->loadclass('link','htmlelements');
    
    // scripts for extjs
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';
    
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $maincss);

    $backLink = new link($this->uri(array('action'=>'showcourseprophist', 'courseid'=>$this->id)));
    $backLink->link = $this->objLanguage->languageText('mod_ads_historylink', 'ads');
    
    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn = '<div id="note">'.$backLink->show().'</div>';
    $rightSideColumn .=  '<div id="commentsBody"></div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();

    $data = $this->objComment->getComments($this->id, $version);
    $myRows = $this->objComment->getNumComments($this->id, $version);
    
    $myComments = "";
    $count = 1;
    if($myRows == 0) {
        $myComments .= "{title: 'Comment', html: 'THERE ARE NO COMMENTS FOR THIS PROPOSAL.'}";
    }
    else {
        foreach($data as $data) {
            $myComments .= "{title: 'Comment ".$count."', html: '".$data['comment']."'}";
            if($count != $myRows) {
                $myComments .= ",";
            }
            $count += 1;
        }
    }
    
    $mainjs = "Ext.onReady(function(){
                    var tabs = new Ext.TabPanel({
                        renderTo: 'commentsBody',
                        width:450,
                        height: 200,
                        activeTab: 0,
                        frame:true,
                        defaults:{autoHeight: true},
                        items:[".$myComments."]
                    });
                });";

    echo "<script type=\"text/javascript\">".$mainjs."</script>";
?>
