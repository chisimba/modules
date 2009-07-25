<?php
//load class
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');


// scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';


$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);

$title= 'Session';
$owner='Instructor';
$startDate='Start Date';
$endDate='End Date';
$enrolled='Enrolled';
$session='Session';


// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);


$addButton = new button('add-session','Add Schedule');
$returnUrl = $this->uri(array('action' => 'addsession'));
$addButton->setOnClick("window.location='$returnUrl'");

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
$rightSideColumn =  '<h1>'.$this->objContext->getTitle($this->contextcode).'</h1><div id="grouping-grid">'.$addButton->show().'<br/><br /></div>';
$cssLayout->setMiddleColumnContent($rightSideColumn);



$data="['Anatomy','David Wafula','2009 07 25 8.00 am','2009 07 25 9.00 am','15 users','Medecine'],";
$data.="['Clinical Medicine','David Wafula','2009 07 25 8.00 am','2009 07 25 9.00 am','15 users','Medecine'],";
$data.="['Java Script','David Wafula','2009 07 25 8.00 am','2009 07 25 9.00 am','15 users','Medecine2'],";
$data.="['Polls','David Wafula','2009 07 25 8.00 am','2009 07 25 9.00 am','15 users','Medecine2'],";

$mainjs = " Ext.onReady(function(){

                    Ext.QuickTips.init();

                    var xg = Ext.grid;

                    // shared reader
                    var reader = new Ext.data.ArrayReader({}, [
                       {name: 'title'},
                       {name: 'owner'},
                       {name: 'startDate'},
                       {name: 'endDate'},
                       {name: 'enrolled'},
                       {name: 'session'},

                    ]);

                    var grid = new xg.GridPanel({
                        store: new Ext.data.GroupingStore({
                            reader: reader,
                            data: xg.data,
                            sortInfo:{field: 'session', direction: \"ASC\"},
                            groupField:'session'
                        }),

                        columns: [
                            {id:'".$title."',header: \"".$title."\", width: 60, dataIndex: 'title'},
                            {header: \"".$owner."\", width: 30,dataIndex: 'owner'},
                            {header: \"".$startDate."\", width: 40, dataIndex: 'startDate'},
                            {header: \"".$endDate."\", width: 40, dataIndex: 'endDate'},
                            {header: \"".$session."\", width: 20, dataIndex: 'session'},
                            {header: \"".$enrolled."\", width: 20, dataIndex: 'enrolled'},
                        ],

                        view: new Ext.grid.GroupingView({
                            forceFit:true,
                            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Items\" : \"Item\"]})'
                        }),

                        frame:true,
                        width: 750,
                        height: 450,
                        collapsible: true,
                        animCollapse: false,
                        title: 'The Schedules',
                        renderTo: 'grouping-grid'
                    });
                });

// Array data for the grids
Ext.grid.data = [
    ".$data."

];


";

echo "<script type=\"text/javascript\">".$mainjs."</script>";
echo $cssLayout->show();
?>
