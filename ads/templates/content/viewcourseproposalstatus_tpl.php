<?php

    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    
    $styleSheet="
    <style type=\"text/css\">
        .x-check-group-alt {
            background: #D1DDEF;
            border-top:1px dotted #B5B8C8;
            border-bottom:1px dotted #B5B8C8;
        }
        div#myForm {
            padding: 0 3em;
        }
    </style>
    ";
    
    //append to the top of the page
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $styleSheet);
    
    // display the extj radio form
    $content = '<div id="message">'.$message.'</div>';
    $content .= '<div id="myForm">';
    $content .= '<div id="form-ct"></div></div>';
    $content .= '<input type="hidden" name="id" id="id" value="'.$this->getParam("id").'">';

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    //Add the table to the centered layer
    $rightSideColumn = $content;
    // Add Right Column
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    //Output the content to the page
    echo $cssLayout->show();
    
    $items = "{boxLabel: 'New', name: 'proposalstatus', inputValue: '0'";
    if($this->getParam("status") == 0) {
        $items .= ", checked: true";
    }
    $items .= "},";
    $items .= "{boxLabel: 'APO comment', name: 'proposalstatus', inputValue: '1'";
    if($this->getParam("status") == 1) {
        $items .= ", checked: true";
    }
    $items .= "},";
    $items .= "{boxLabel: 'Faculty committee', name: 'proposalstatus', inputValue: '2'";
    if($this->getParam("status") == 2) {
        $items .= ", checked: true";
    }
    $items .= "},";
    $items .= "{boxLabel: 'Faculty', name: 'proposalstatus', inputValue: '3'";
    if($this->getParam("status") == 3) {
        $items .= ", checked: true";
    }
    $items .= "}";

    $mainjs = "/*!
                 * Ext JS Library 3.0.0
                 * Copyright(c) 2006-2009 Ext JS, LLC
                 * licensing@extjs.com
                 * http://www.extjs.com/license
                 */
                Ext.onReady(function(){

                    Ext.QuickTips.init();

                    Ext.form.Field.prototype.msgTarget = 'side';

                    var radioGroup = {
                            xtype: 'radiogroup',
                            itemCls: 'x-check-group-alt',
                            fieldLabel: 'Proposal Status',
                            columns: 1,
                            items: [$items]
                    };
                    var id = document.getElementById('id').value;

                    // combine all that into one huge form
                    var fp = new Ext.FormPanel({
                        standardSubmit: true,
                        url: 'index.php?module=ads&action=submitproposalstatus',
                        title: 'Change the status of the proposal',
                        frame: true,
                        labelWidth: 110,
                        width: 600,
                        renderTo:'form-ct',
                        bodyStyle: 'padding:0 0 0;',
                        items: [
                            radioGroup
                        ],
                        buttons: [{
                            text: 'Save',
                            handler: function(){
                                if (fp.getForm().isValid()) {
                                    if (fp.url)
                                        fp.getForm().getEl().dom.action = fp.url + \"&id=\" + id;

                                    fp.getForm().submit();
                                }
                            }
                        },{
                            text: 'Reset',
                            handler: function(){
                                fp.getForm().reset();
                            }
                        }]
                    });
                });";

    echo "<script type=\"text/javascript\">$mainjs</script>";
?>
