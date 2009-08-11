<?php
    //load class
    $this->loadclass('link','htmlelements');
    $objIcon= $this->newObject('geticon','htmlelements');

    // scripts
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';
    $jQuery = '<script language="JavaScript" src="'.$this->getResourceUri('js/jquery-1.2.6.min').'" type="text/javascript"></script>';

    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $maincss);
    $this->appendArrayVar('headerParams', $jQuery);

    $curEdit_1 = $this->objLanguage->languageText('mod_ads_curedit_1', 'ads');
    $curEdit_2 = $this->objLanguage->languageText('mod_ads_curedit_2', 'ads');

    $version = $this->objLanguage->languageText('mod_ads_historyversion', 'ads');
    $editor = $this->objLanguage->languageText('mod_ads_historyeditor', 'ads');
    $lastUpdated = $this->objLanguage->languageText('mod_ads_historyupdate', 'ads');
    $comments = $this->objLanguage->languageText('mod_ads_historycomments', 'ads');

    $verarray = $this->objDocumentStore->getVersion($this->id, $this->objUser->userId());
    if ($verarray['status'] == 'unsubmitted' && strcmp($verarray['currentuser'],$this->objUser->email($this->objUser->userId())) == 0) {
        $link = new link($this->uri(array('action'=>'submitproposal','courseid'=>$this->id)));
        $link->link = $curEdit_1." ".$curEdit_2;
        $data = $link->show();
    }

    $version = new link();
    $comment = new link();
    $send = new link();
    $send->href = "#";
    $send->extra = "id=\"show-btn\"";
    $objIcon->setIcon('forward');
    $send->link=$objIcon->show();

    // labels for the forward form
    $lname = "Last Name";
    $fname = "First Name";
    $email = "Email Address";
    $phone = "Phone #";

    $courseName = $this->objCourseProposals->getTitle($this->id);
    $historyData = $this->objDocumentStore->getHistory($this->id);

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id="note">NB: Click on latest version to edit and create a new version.<br>'.$data;
    $rightSideColumn .= '</div><div id ="proposal-history">';

    if (strcmp($verarray['currentuser'],$this->objUser->email($this->objUser->userId())) == 0) {
        $rightSideColumn .= $send->show().'<br><br>';
    }

    $rightSideColumn .= '<div id="history-grid">';
    $rightSideColumn .= '</div>';
    $rightSideColumn .= '<div id="send-win" class="x-hidden">

                            <div class="x-window-header">Send Document To User</div>
                            <div id="send-tab">
                                <!-- Auto create tab 1 -->
                                <div class="x-tab" title="Please Fill Out Info"></div>
                            </div>
                        </div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);
    echo $cssLayout->show();

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
            $version->link($this->uri(array('action'=>'viewform','courseid'=>$this->id, 'formnumber'=>$this->allForms[0], 'edit'=>'NO')));
            $version->link = $value['version'].".00";
            $curVersion = $version->show();
        }

        $comment->link($this->uri(array('action'=>'viewComments','courseid'=>$this->id, 'version'=>$value['version'])));
        $comment->link = 'Click to View Comments';

        $editor = $this->objUser->fullname(trim($this->objDocumentStore->getUserId($value['currentuser'])));
        if($editor == null) {
            $editor = $this->objDocumentStore->getFullName($value['currentuser'], $this->id);echo "HELL: ".$value['currentuser']." :".$editor;
        }
        
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
    $submitUrl = $this->uri(array('action'=>'sendProposal','edit'=>true,'id'=>$this->id) );

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
                            {header: \"Editor\", width: 150, dataIndex: 'editor'},
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

    $sendjs = "/*!
                 * Ext JS Library 3.0.0
                 * Copyright(c) 2006-2009 Ext JS, LLC
                 * licensing@extjs.com
                 * http://www.extjs.com/license
                 */
                Ext.onReady(function(){
                    var win;
                    var button = Ext.get('show-btn');
                    var myForm = new Ext.FormPanel({
                                    labelWidth: 125,
                                    url:'".str_replace("amp;", "", $submitUrl)."',
                                    frame:true,
                                    title: 'User Details',
                                    bodyStyle:'padding:5px 5px 0',
                                    width: 350,
                                    defaults: {width: 230},
                                    defaultType: 'textfield',

                                    items: [{
                                            fieldLabel: '".$lname."',
                                            name: 'lname',
                                            allowBlank: false
                                        },{
                                            fieldLabel: '".$fname."',
                                            name: 'fname',
                                            allowBlank: false
                                        },{
                                            fieldLabel: '".$email."',
                                            name: 'email',
                                            allowBlank: false
                                        },{
                                            fieldLabel: '".$phone."',
                                            name: 'phone',
                                            allowBlank: false
                                        }
                                    ]
                                });

                    button.on('click', function(){
                        // create the window on the first click and reuse on subsequent clicks
                        if(!win){
                            win = new Ext.Window({
                                applyTo:'send-win',
                                layout:'fit',
                                width:500,
                                height:300,
                                closeAction:'hide',
                                plain: true,
                                items: myForm,
                                buttons: [{
                                    text:'Submit',
                                    handler: function() {
                                        if (myForm.getForm().isValid()) {
                                            if (myForm.url) {
                                                myForm.getForm().getEl().dom.action = myForm.url;
                                                test = myForm.getForm().submit();
                                                win.hide();
                                                //window.location.reload();
                                            }
                                        }
                                    }
                                },{
                                    text: 'Close',
                                    handler: function(){
                                        win.hide();
                                    }
                                }]
                            });
                        }
                        win.show(this);
                    });
                });";

    echo "<script type=\"text/javascript\">".$mainjs."</script>";
    echo "<script type=\"text/javascript\">".$sendjs."</script>";
?>