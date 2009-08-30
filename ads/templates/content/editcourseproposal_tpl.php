<?php

    // security check - must be included in all scripts
    if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }
    // end security check

    
    //language stuff
    $faculty = $objLanguage->languageText('mod_ads_faculty','ads');
    $unitName = $objLanguage->languageText('mod_ads_unitname','ads');

    // scripts for extjs
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    
    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id="courseProposal"></div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();



    $submitUrl = $this->uri(array('action'=>'savecourseproposal','edit'=>true,'id'=>$this->id) );
    $cancelUrl = $this->uri(array('action'=>'NULL'));
    
    $courseData = $this->objCourseProposals->getCourseProposal($this->id);
    $facultyVal = $courseData['faculty'];
    $unitNameVal = $courseData['title'];
$faculties=
    "
var faculties= [
        ['Commerce, Law and Management'],
        ['Engineering and the Built Environment'],
        ['Health Sciences'],
        ['Humanities'],
        ['Science']
      ]

   var facutlystore = new Ext.data.ArrayStore({
        fields: ['faculty'],
        data : faculties
    });
    var facultyField = new Ext.form.ComboBox({
        store: facutlystore,
        displayField:'faculty',
        fieldLabel:'Faculty',
        typeAhead: true,
        mode: 'local',
        editable:false,
        allowBlank: false,
        value:'".$facultyVal."',
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select faculty...',
        selectOnFocus:true,
        name : 'faculty'

    });
";


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

            items: [
                    facultyField,
                    {
                    fieldLabel: '".$unitName."',
                    name: 'title',
                    value: '".$unitNameVal."',
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
    echo '<script type="text/javascript">'.$faculties.$script.'</script>';
?>