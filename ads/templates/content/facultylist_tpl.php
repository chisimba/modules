<?php
//load class
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');
$grid = $this->objFaculty->getFacultyData();

// scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';
$facultylistjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/facultylist.js').'" type="text/javascript"></script>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);
$this->appendArrayVar('headerParams', $facultylistjs);

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$addModerator = new button('addModerator', 'Add Moderator');
$addFaculty = new button('addFaculty', 'Add Faculty');
$addModerator->setId('addmoderator-btn');
$addFaculty->setId('addfaculty-btn');

$allFaculty = $this->objFaculty->getAllFaculty();
$facultyRC = $this->objFaculty->getFacultyRC();

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
//$rightSideColumn =  '<h1>Faculty Listing</h1>';
$rightSideColumn ='<div id ="facultylist">'.$addFaculty->show()."&nbsp;&nbsp;".$addModerator->show();
$renderSurface .= '<div id="addmoderator-win" class="x-hidden"><div class="x-window-header"></div></div>';
$renderSurface .= '<div id="addfaculty-win" class="x-hidden"><div class="x-window-header"></div></div>';
$renderSurface .= '<div id="facultylisting"></div>';
$rightSideColumn .=$renderSurface;
$rightSideColumn .= '</div>';
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();

// language stuff
$faculty = $this->objLanguage->languageText('mod_ads_faculty', 'ads');
$moderatorName = $this->objLanguage->languageText('mod_ads_moderatorname','ads');




$facultyData = "";
$count = 1;
foreach($allFaculty as $data) {
    if($count != $facultyRC) {
        $facultyData .= "['".$data['name']."'],";
    }
    else {
        $facultyData .= "['".$data['name']."']";
    }

    $count++;
}

$submitModeratorUrl = $this->uri(array('action'=>'savemoderator'));
$submitFacultyUrl = $this->uri(array('action'=>'savefaculty'));


$modFaculties=
"   var modFaculties= [
        $facultyData
      ]
    
   var modFacultyStore = new Ext.data.ArrayStore({
        fields: ['faculty'],
        data : modFaculties
    });

    var modFacultyField = new Ext.form.ComboBox({
        store: modFacultyStore,
        displayField:'faculty',
        fieldLabel:'Faculty',
        typeAhead: true,
        mode: 'local',
        editable:false,
        allowBlank: false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select faculty...',
        selectOnFocus:true,
        name : 'faculty'
    });
";

$addModeratorWindowJS=
"   var addModeratorWin;
    var modBtn = Ext.get('addmoderator-btn');
    
    modBtn.on('click', function() {
        if(!addModeratorWin){
            addModeratorWin = new Ext.Window({
                applyTo:'addmoderator-win',
                layout:'fit',
                width:500,
                height:250,
                x:250,
                y:150,
                closeAction:'destroy',
                plain: true,

                items: moderatorForm,
                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (moderatorForm.url)
                            moderatorForm.getForm().getEl().dom.action = moderatorForm.url;

                        moderatorForm.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                       addModeratorWin.hide();
                    }
                }]
            });
        }
        addModeratorWin.show(this);
    });
";

$moderatorForm =
 "
       var moderatorForm = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125,
            url:'".str_replace("amp;", "", $submitModeratorUrl)."',
            title: 'Add Faculty Moderator',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [
                    modFacultyField,
                    {
                    fieldLabel: '".$moderatorName."',
                    name: 'moderator',
                    id: 'moderator_title',
                    allowBlank: false
                }
            ]
        });
 ";

$addFacultyWindowJS=
"   var addFacultyWin;
    var facultyAddBtn = Ext.get('addfaculty-btn');
    
    facultyAddBtn.on('click', function() {
        if(!addFacultyWin){
            addFacultyWin = new Ext.Window({
                applyTo:'addfaculty-win',
                layout:'fit',
                width:400,
                height:150,
                x:250,
                y:150,
                closeAction:'destroy',
                plain: true,

                items: facultyAddForm,
                buttons: [{
                    text:'Save',
                    handler: function(){
                        if (facultyAddForm.url)
                            facultyAddForm.getForm().getEl().dom.action = facultyAddForm.url;

                        facultyAddForm.getForm().submit();
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                       addFacultyWin.hide();
                    }
                }]
            });
        }
        addFacultyWin.show(this);
    });
";

$facultyAddForm =
 "
       var facultyAddForm = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 100,
            url:'".str_replace("amp;", "", $submitFacultyUrl)."',
            title: 'Add Faculty',
            bodyStyle:'padding:5px 5px 0',
            width: 350,
            defaultType: 'textfield',

            items: [{
                    fieldLabel: '".$faculty."',
                    name: 'addfaculty',
                    id: 'addfaculty_title',
                    allowBlank: false,
                    width: 250
            }]
        });
 ";

$mainjs =
"<script type=\"text/javascript\">
    Ext.onReady(function(){
        ".$facultyAddForm."
        ".$addFacultyWindowJS."
        ".$modFaculties."
        ".$moderatorForm."
        ".$addModeratorWindowJS."
    });
</script>";
$this->appendArrayVar('headerParams', $mainjs);

$tablescript = "<script type=\"text/javascript\">loadTable($grid);</script>";
$this->appendArrayVar('headerParams', $tablescript);
?>
