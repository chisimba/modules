<?php
    //load class
    $this->loadclass('link','htmlelements');
    $objIcon= $this->newObject('geticon','htmlelements');

    // scripts
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';

    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $maincss);

    $curEdit_1 = $this->objLanguage->languageText('mod_ads_curedit_1', 'ads');
    $curEdit_2 = $this->objLanguage->languageText('mod_ads_curedit_2', 'ads');

    $version = $this->objLanguage->languageText('mod_ads_historyversion', 'ads');
    $editor = $this->objLanguage->languageText('mod_ads_historyeditor', 'ads');
    $lastUpdated = $this->objLanguage->languageText('mod_ads_historyupdate', 'ads');
    $comments = $this->objLanguage->languageText('mod_ads_historycomments', 'ads');

    $verarray = $this->objDocumentStore->getVersion($this->id, $this->objUser->userId());
    if ($verarray['status'] == 'unsubmitted' && $verarray['currentuser'] == $this->objUser->userId()) {
        $link = new link($this->uri(array('action'=>'submitproposal','courseid'=>$this->id)));
        $link->link = $curEdit_1." ".$curEdit_2;
        $data = $link->show();
    }

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id="note">NB: Click on last version to edit and create a new version.<br>'.$data;
    $rightSideColumn .= '</div><div id ="proposal-history"><div id="history-grid">';
    $rightSideColumn .= '</div></div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();

    $version = new link();
    $comment = new link();

    $courseName = $this->objCourseProposals->getTitle($this->id);
    $historyData = $this->objDocumentStore->getHistory($this->id);

    $numRows = 0;

    foreach($historyData  as $value) {
        $numRows += 1;
    }

    $data = "";
    $curRow = 1;
    foreach($historyData  as $value) {
        if($curRow == $numRows) {
            $version->link($this->uri(array('action'=>'viewform','courseid'=>$this->id, 'formnumber'=>$this->allForms[0])));
            $version->link = $value['version'].".00";
            $curVersion = $version->show();
        }
        else {
            $curVersion = $value['version'].".00";
        }

        $comment->link($this->uri(array('action'=>'viewComments','courseid'=>$this->id, 'version'=>$value['version'])));
        $comment->link = 'Click to View Comments';
        $editor = $this->objUser->fullname($value['currentuser']);
        $dateUpdated = date("m/d/Y H:m:s" );

        $data .= "['".$curVersion."',";
        $data .= "'".$editor."',";
        $data .= "'".$dateUpdated."',";
        $data .= "'".$comment->show()."']";
        if($curRow != $numRows) {
            $data .= ",";
        }
        $curRow += 1;
    }

    $mainjs = "/*!
                 * Ext JS Library 3.0.0
                 * Copyright(c) 2006-2009 Ext JS, LLC
                 * licensing@extjs.com
                 * http://www.extjs.com/license
                 */
                Ext.onReady(function(){
                    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

                    var myData = [".$data."];

                    // create the data store
                    var store = new Ext.data.ArrayStore({
                        fields: [
                           {name: 'version'},
                           {name: 'editor'},
                           {name: 'lastChange'},
                           {name: 'comments'}
                        ]
                    });
                    store.loadData(myData);

                    // create the Grid
                    var grid = new Ext.grid.GridPanel({
                        store: store,
                        columns: [
                            {id:'version',header: \"Version\", width: 75, dataIndex: 'version'},
                            {header: \"Editor\", width: 180, dataIndex: 'editor'},
                            {header: \"Last Updated\", width: 120, dataIndex: 'lastChange'},
                            {header: \"Comments\", width: 150, dataIndex: 'comments'}
                        ],
                        stripeRows: true,
                        height:350,
                        width:600,
                        title:'".$courseName."'
                    });
                    grid.render('history-grid');
                });";

    echo "<script type=\"text/javascript\">".$mainjs."</script>";

?>