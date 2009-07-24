<?php
    //language stuff
    $faculty = $objLanguage->languageText('mod_ads_faculty','ads');
    $unitName = $objLanguage->languageText('mod_ads_unitname','ads');

    // scripts for extjs
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/ext-base.js').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/ext-all.js').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('scripts/ext-all.css').'"/>';
    $extalldebug = '<script language="JavaScript" src="'.$this->getResourceUri('scripts/ext-all-debug.js').'" type="text/javascript"></script>';
    
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $extalldebug);
    
    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id="courseProposal"></div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();



    $submitUrl = $this->uri(array('action'=>'savecourseproposal'));
    $cancelUrl = $this->uri(array('action'=>'NULL'));
    
    $script = "
    Ext.onReady(function(){
        Ext.QuickTips.init();

        var simple = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125, // label settings here cascade unless overridden
            url:'".str_replace("amp;", "", $submitUrl)."',
            frame:true,
            title: 'Add  New Course Proposal',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [{
                    fieldLabel: '".$faculty."',
                    name: 'faculty',
                    allowBlank: false
                },{
                    fieldLabel: '".$unitName."',
                    name: 'title',
                    id: 'input_title',
                    allowBlank: false
                }
            ],

            buttons: [{
                text: 'Save',
                handler: function(){
                    if (simple.getForm().isValid()) {
                        if (simple.url)
                            simple.getForm().getEl().dom.action = simple.url;
                        
                        simple.getForm().submit();
                    }
                }
            },{
                text: 'Reset',
                handler: function(){
                    simple.getForm().reset();
                }
            }]
        });

        simple.render('courseProposal');
    });";
    echo '<script type="text/javascript">'.$script.'</script>';
?>