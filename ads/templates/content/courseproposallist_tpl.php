<?php
    //load class
    $this->loadclass('link','htmlelements');
    $objIcon= $this->newObject('geticon','htmlelements');

    // objects
    $courseProposals = $this->objCourseProposals->getCourseProposals($this->objUser->userId());

    // scripts
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/ext-base.js').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/ext-all.js').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('scripts/ext-all.css').'"/>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('scripts/courseproposal.css').'"/>';
    
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $maincss);

    // language stuff
    $note = $this->objLanguage->languageText('mod_ads_note', 'ads');
    $proposalTitle=$this->objLanguage->languageText('mod_ads_proposals','ads');
    $title = $this->objLanguage->languageText('mod_ads_title', 'ads');
    $dateCreated = $this->objLanguage->languageText('mod_ads_datecreated', 'ads');
    $owner = $this->objLanguage->languageText('mod_ads_owner', 'ads');
    $status = $this->objLanguage->languageText('mod_ads_status', 'ads');
    $currVersion = $this->objLanguage->languageText('mod_ads_currversion', 'ads');
    $lastEdit = $this->objLanguage->languageText('mod_ads_lastedit', 'ads');
    $edit = $this->objLanguage->languageText('mod_ads_edit', 'ads');
    $faculty = $this->objLanguage->languageText('mod_ads_faculty', 'ads');
    $curEdit_1 = $this->objLanguage->languageText('mod_ads_curedit_1', 'ads');
    $curEdit_2 = $this->objLanguage->languageText('mod_ads_curedit_2', 'ads');


    $addButton = new button('add','Add Proposal');
    $returnUrl = $this->uri(array('action' => 'addcourseproposal'));
    $addButton->setOnClick("window.location='$returnUrl'");

    //prints out add comment message
    if ($this->addCommentMessage){
        $message = "<span id=\"commentSuccess\">".$this->objLanguage->languageText('mod_ads_commentSuccess', 'ads')."</span><br />";
        $this->addCommentMessage = false;
    } else $message = "";
    $content = $message;

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id ="proposal-note">'.$content.$note.'</div><div id="grouping-grid">'.$addButton->show().'<br /><br /></div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();

    $statusLink = new link();
    $titleLink = new link();
    $deleteLink=new link();
    $editLink=new link();
    $submitLink = new link();
    $reviewLink=new link();
    $commentLink=new link();

    $data = "";
    $numberOfRows = $this->objCourseProposals->getNumberOfCourses();

    foreach($courseProposals as $value) {
        $verarray = $this->objDocumentStore->getVersion($value['id'], $this->objUser->userId());

        $titleLink->link($this->uri(array('action'=>'viewform','courseid'=>$value['id'], 'formnumber'=>$this->allForms[0])));
        $titleLink->link=$value['title'];

        $statusLink->link($this->uri(array('action'=>'viewcourseproposalstatus','id'=>$value['id'])));
        switch($value['status']) {
            case 0: $statusLink->link='New';
                    break;
            case 1: $statusLink->link='Under Review';
                    break;
            case 2: $statusLink->link='Accepted';
                    break;
            case 3: $statusLink->link='Rejected';
                    break;
            default: $statusLink->link= 'New';
        }

        $deleteLink->link($this->uri(array('action'=>'deletecourseproposal','id'=>$value['id'])));
        
        $editLink->link($this->uri(array('action'=>'editcourseproposal','id'=>$value['id'])));
        
        //review
        $reviewLink->link($this->uri(array('action'=>'reviewcourseproposal','id'=>$value['id'])));

        $data .= "['".$titleLink->show();
        if ($verarray['status'] == 'unsubmitted' && $verarray['currentuser'] == $this->objUser->userId()) {
            $link = new link($this->uri(array('action'=>'submitproposal','courseid'=>$value['id'])));
            $link->link = "<br />".$curEdit_1."<br />".$curEdit_2;
            $data .= $link->show();
        }
        
        $data .= "',";
        $data .= "'".$value['creation_date']."',";
        $data .= "'".$this->objUser->fullname($value['userid'])."',";
        $data .= "'".$statusLink->show()."',";
        $data .= "'".$verarray['version'] .".00',";
        $data .= "'".$this->objUser->fullname($verarray['currentuser'])."',";

        $objIcon->setIcon('delete');
        $deleteLink->link=$objIcon->show();
        $data .= "'".$deleteLink->show();
        
        $objIcon->setIcon('edit');
        $editLink->link=$objIcon->show();
        $data .= $editLink->show();

        $objIcon->setIcon('view');
        $reviewLink->link=$objIcon->show();
        $data .= $reviewLink->show();
        
        if (strstr($this->objUser->fullname($verarray['currentuser']),'Administrative User')) {
            $commentLink->link($this->uri(array('action'=>'addcomment',
                                                'id'=>$value['id'],
                                                'title'=>$value['title'],
                                                'date'=>$value['creation_date'],
                                                'owner'=>$this->objUser->fullname($value['userid']),
                                                'status'=>$status,
                                                'version'=>$verarray['version'],
                                                'lastedit'=>$this->objUser->fullname($verarray['currentuser']))));
            $objIcon->setIcon('comment');
            $commentLink->link = $objIcon->show();
            $data .= $commentLink->show();
        }
        $data .= "',";

        $data .= "'".$value['faculty']."'";
        $data .= "]";
        if($value['puid'] != $numberOfRows) {
            $data .= ",";
        }

    }

    $mainjs = "/*!
                 * Ext JS Library 3.0.0
                 * Copyright(c) 2006-2009 Ext JS, LLC
                 * licensing@extjs.com
                 * http://www.extjs.com/license
                 */
                Ext.onReady(function(){

                    Ext.QuickTips.init();

                    var xg = Ext.grid;

                    // shared reader
                    var reader = new Ext.data.ArrayReader({}, [
                       {name: 'title'},
                       {name: 'dateCreated'},
                       {name: 'owner'},
                       {name: 'status'},
                       {name: 'currVersion'},
                       {name: 'lastEdit'},
                       {name: 'edit'},
                       {name: 'faculty'}
                    ]);

                    var grid = new xg.GridPanel({
                        store: new Ext.data.GroupingStore({
                            reader: reader,
                            data: xg.Data,
                            sortInfo:{field: 'title', direction: \"ASC\"},
                            groupField:'faculty'
                        }),

                        columns: [
                            {id:'".$title."',header: \"".$title."\", width: 80, dataIndex: 'title'},
                            {header: \"".$dateCreated."\", width: 45},
                            {header: \"".$owner."\", width: 40},
                            {header: \"".$status."\", width: 25, dataIndex: 'status'},
                            {header: \"".$currVersion."\", width: 20, dataIndex: 'currVersion'},
                            {header: \"".$faculty."\", width: 30, dataIndex: 'faculty'},
                            {header: \"".$lastEdit."\", width: 40, dataIndex: 'lastEdit'},
                            {header: \"".$edit."\", width: 60, dataIndex: 'edit'}
                        ],

                        view: new Ext.grid.GroupingView({
                            forceFit:true,
                            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Items\" : \"Item\"]})'
                        }),

                        frame:true,
                        width: 900,
                        height: 450,
                        collapsible: true,
                        animCollapse: false,
                        title: '".$proposalTitle."',
                        renderTo: 'grouping-grid'
                    });
                });



                // Array data for the grids
                Ext.grid.Data = [".$data."];";

    echo "<script type=\"text/javascript\">".$mainjs."</script>";
?>
