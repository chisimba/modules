<?php
    // load classes
    $this->loadclass('link','htmlelements');
    // scripts
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';

    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $maincss);

    echo '<div id="tree-div" style="padding-top: 2em;"></div>
          <div id="myLayout">
            <div id="send-win" class="x-hidden">
                <div class="x-window-header">Send Document To User</div>
                <div id="send-tab">
                    <!-- Auto create tab 1 -->
                    <div class="x-tab" title="Please Fill Out Info"></div>
                </div>
            </div>
          </div>';

    // labels for the forward form (popup window)
    $lname = "Last Name";
    $fname = "First Name";
    $email = "Email Address";
    $phone = "Phone #";
    $nextForm = new link($this->uri(array('action'=>'viewform','courseid'=>$this->id, 'formnumber'=>$this->allForms[0])));
    $submitForm = new link($this->uri(array('action'=>'submitproposal','courseid'=>$this->id)));
    $submitUrl = new link($this->uri(array('action'=>'sendProposal','edit'=>true,'id'=>$this->id)));
    
    $mainjs =  "<script type='text/javascript'>
            
            Ext.onReady(function() {
                var tree = new Ext.tree.TreePanel({
                                useArrows: true,
                                autoScroll: true,
                                animate: true,
                                enableDD: true,
                                containerScroll: true,
                                border: false,
                                rootVisible: false,

                                // auto create TreeLoader
                                dataUrl: '".str_replace("amp;", "",$this->uri(array('action'=>'gethistorydata', 'courseid'=>$this->id, 'formnumber'=>$this->allforms[0])))."',
                                root: {
                                    nodeType: 'async',
                                    text: 'Ext JS',
                                    draggable: false,
                                    id: 'source'
                                },
                                listeners: {
                                    click: function(n) {
                                        populateForm(n);
                                    }
                                }

                            });
                tree.getRootNode().expand();


                var form = {
                    xtype: 'form', // since we are not using the default 'panel' xtype, we must specify it
                    id: 'form-panel',
                    labelWidth: 75,
                    bodyStyle: 'padding:15px',
                    width: 500,
                    labelPad: 20,
                    defaultType: 'textfield',
                    items: [
                        new Ext.form.DateField({
                            fieldLabel: 'Date',
                            name: 'date',
                            id: 'myDate',
                            allowBlank:false
                        })
                        ,{
                            fieldLabel: 'Edited By',
                            name: 'editor',
                            id: 'myEditor',
                            allowBlank: false
                        },
                        new Ext.form.DisplayField({
                            fieldLabel: 'Comments',
                            name: 'comments',
                            id: 'myComments'
                        })
                    ],
                    buttons: [
                        {
                            text: 'Foward',
                            handler: function()
                                forwardProposal()
                        },
                        {
                            text: 'Forward to Moderator',
                            handler: function()
                                forwardProposalToModerator()
                        },
                        {
                            text: 'Save Changes',
                            handler: function()
                                saveChanges()
                        },
                        {
                            text: 'Edit Proposal',
                            handler: function()
                                editProposal()
                        },
                        {
                            text: 'Submit Proposal',
                            handler: function()
                                submitProposal()
                        }
                    ]
                };

                var propHist = {
                    title: 'Proposal History',
                    width: 250,
                    height: 300,
                    items: [tree]
                };

                var propForm = {
                    title: 'Proposal Form',
                    columnWidth: .6,
                    height: 300,
                    items: [form]
                };

                new Ext.Viewport({
                    title: 'Table Layout',
                    applyTo: 'myLayout',
                    region: 'center',
                    layout: 'column',
                    miheight: 200,
                    defaults: {
                        bodyStyle:'padding:20px'
                    },
                    items: [propHist, propForm]
                });

                // populate the fields in proposal form with data
                jQuery.getJSON('".str_replace("amp;", "",$this->uri(array('action'=>'gethistorydata', 'courseid'=>$this->id, 'data'=>true)))."', function(json) {
                    jQuery('#myDate').val(json.datemodified);
                    jQuery('#myEditor').val(json.editor);
                    jQuery('#myComments').append(json.comment);
                });
           });

           function populateForm(n) {
                // get the data clicked on
                var propChosen = n.attributes.text,
                    eachSplit = propChosen.split('_'),
                    date = eachSplit[0],
                    version = eachSplit[1],
                    url = '".str_replace("amp;", "",$this->uri(array('action'=>'gethistorydata', 'courseid'=>$this->id, 'data'=>true)))."';

                    url = url + '&version=' + version;

                // get data from database and populate form fields with query data
                jQuery.getJSON(url, function(json) {
                    var editor = json.editor,
                        comments = json.comment;

                    jQuery('#myDate').val(date);
                    jQuery('#myEditor').val(editor);
                    jQuery('#myComments').replaceWith(comments);
                });
           }

            function forwardProposal() {
                var win;
                var button = Ext.get('show-btn');
                var myForm = new Ext.FormPanel({
                                labelWidth: 125,
                                url:'".str_replace("amp;", "", $submitUrl->show())."',
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
                                        //myForm.getForm().getEl().dom.action = myForm.url;
                                        //test = myForm.getForm().submit();
                                        win.hide();
                                        window.location.reload();
                                        Ext.MessageBox.alert('sending info');
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
            }

            function forwardProposalToModerator() {

            }

            function saveChanges() {

            }

            function editProposal() {
                window.location.href = '".str_replace("amp;", "",$nextForm->href)."';
            }

            function submitProposal() {
                window.location.href = '".str_replace("amp;", "", $submitForm->href)."';
            }
    </script>";

    $this->appendArrayVar('headerParams', $mainjs);
?>