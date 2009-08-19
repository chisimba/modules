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

    echo '<div id="tree-div" style="padding-top: 2em;"></div><div id="myLayout"><div id="test"></div></div>';
    

    $this->id = 'gen13Srv5Nme8_6714_1250414805';
    $this->version = '1';
    $comment = new link();
    $comment->link($this->uri(array('action'=>'viewComments','courseid'=>$this->id, 'version'=>$this->version)));
    $comment->link = 'Click to View Comments';

    echo "<script type='text/javascript'>
            //
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
                                dataUrl: '".str_replace("amp;", "",$this->uri(array('action'=>'gethistorydata')))."',
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
                    width: 400,
                    labelPad: 20,
                    defaultType: 'textfield',
                    items: [
                        new Ext.form.DateField({
                            fieldLabel: 'Date',
                            name: 'date',
                            width: 200,
                            allowBlank:false
                        })
                        ,{
                            fieldLabel: 'Edited By',
                            name: 'editor',
                            allowBlank: false
                        },
                        new Ext.form.DisplayField({
                            fieldLabel: 'Comments',
                            name: 'comments',
                            value: '".str_replace("amp;", "", $comment->show())."',
                            ref: '#',
                            listeners: {
                                click: function(n) {
                                    Ext.Msg.alert('hello world');
                                }
                            }
                        })
                    ],
                    buttons: [{text: 'Foward'},{text: 'Forward to Moderator'}, {text: 'Save Changes'}]
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
           });


           function populateForm(n) {
                // get the data clicked on
                var propChosen = n.attributes.text;
                //split it on _ to get the data and version
                Ext.Msg.alert('Navigation Tree Click', 'You clicked: ' + propChosen);
           }

           function viewComments(n) {
                // get the data clicked on
                var propChosen = n.attributes.text;
                //split it on _ to get the data and version
                Ext.Msg.alert('Navigation Tree Click', 'You clicked: ' + propChosen);
           }
    </script>";
?>