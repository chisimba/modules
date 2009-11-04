<?php

//load class
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');

// scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';

$courseproposaljs = '<script language="JavaScript" src="'.$this->getResourceUri('js/courseproposal.js','ads').'" type="text/javascript"></script>';
$proposaldetailsjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/proposaldetails.js','ads').'" type="text/javascript"></script>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);

$this->appendArrayVar('headerParams', $courseproposaljs);
$this->appendArrayVar('headerParams', $proposaldetailsjs);

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

$objCourseProposals = $this->getObject('dbcourseproposals');
$objUser = $this->getObject ( 'user', 'security' );
$courseProposals =$objCourseProposals->getCourseProposals($objUser->userId());
$objFaculty = $this->getObject('dbfaculty');
$objSchool = $this->getObject('dbfacultyschool');
$facultyList = $objFaculty->getAllFaculty();
$schoolList = $objSchool->getSchoolData();
$facultyData = "";
$tmpFacultyData = "";
$count = 1;
$total=count($facultyList);
foreach($facultyList as $data) {
  $facultyData.="['".$data['name']."','".$data['id']."']";
  $tmpFacultyData.="[\'".$data['name']."\',\'".$data['id']."\']";
  if($count < $total){
      $facultyData.=",";
      $tmpFacultyData.=",";
  }
   $count++;
}

$schoolData = "";
$count = 1;
$total=count($schoolList);
foreach($schoolList as $data) {
  $schoolData.="['".$data['schoolname']."','".$data['id']."']";
  if($count < $total){
      $schoolData.=",";
  }
   $count++;
}

$statuscodes=  array(
                "0"=> 'Proposal Phase',
                "1"=>'APO Comment',
                "2"=>'Faculty Subcommittee Approval',
                "3"=>'Faculty Board Approval'
);

$addButton = new button('add','Add Proposal');
$adminButton = new button('addmin','Admin');
$adminButton->setId('admin-btn');
$reportButton= new button('viewreport', 'View Report');
$reportUrl = $this->uri(array('action' => 'viewreport'));
$reportButton->setId('viewreport-btn');
$reportButton->setOnClick("window.location='$reportUrl'");
$addModerator = new button('addModerator', 'Add Moderator');
$addFaculty = new button('addFaculty', 'Faculty List');
$returnUrl = $this->uri(array('action' => 'addcourseproposal'));
//$addButton->setOnClick("window.location='$reportUrl'");
$addButton->setId('addproposal-btn');
$addModerator->setId('addmoderator-btn');
$addFaculty->setId('addfaculty-btn');
$commentAdminButton = new button('commentAdmin', 'Status Admin');
$commentAdminButton->setId('commentadmin');
$configUrl = $this->uri(array('action'=>'configuration'));
$configButton = new button('config', 'Options');
$configButton->setId('configBtn');
$configButton->setOnClick("window.location='$configUrl'");



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
$rightSideColumn =  '<h1>Course/Unit Proposals</h1><div id ="proposal-note">';
$rightSideColumn .=$content.$note;
$adminButtons=$this->objUser->isAdmin()?$adminButton->show():"";
$rightSideColumn .='</div><div id="grouping-grid">'.$addButton->show()."&nbsp;&nbsp;".$adminButtons."&nbsp;&nbsp;"."&nbsp;&nbsp;".$configButton->show().'<br /><br /></div>';
//where we render the 'popup' window
$renderSurface='<div id="addsession-win" class="x-hidden"><div class="x-window-header"></div></div>';
$renderSurface.='<div id="editfaculty-win" class="x-hidden"><div class="x-window-header"></div></div>';
$rightSideColumn.=$renderSurface;
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
$cfac=1;
$total=count($courseProposals);
foreach($courseProposals as $value) {
    
    $verarray = $this->objDocumentStore->getVersion($value['id'], $this->objUser->userId());
    $titleLink->link($this->uri(array('action'=>'showcourseprophist', 'courseid'=>$value['id'],'selectedtab'=>'0')));
    $titleLink->link=$value['title'];

    $statusLink->link($this->uri(array('action'=>'viewcourseproposalstatus','id'=>$value['id'], 'status'=>$value['status'])));
    $statusLink->link=$statuscodes[$value['phase']];
    $deleteLink->link("#");
    $editLink->link("#");
    
    $reviewLink->link($this->uri(array('action'=>'reviewcourseproposal','id'=>$value['id'])));
    $data .= "['".$titleLink->show()."',";
    $data .= "'".$value['creation_date']."',";

    $data .= "'".$this->objUser->fullname($value['userid'])."',";
    $statusShow="'".$statuscodes[$value['phase']]."',";
    $data .=$statusShow;
    $data .= "'".$this->objFaculty->getFacultyName($value['faculty'])."',";
    $objIcon->setIcon('delete');
    $delValJS="deleteProposal(\'".$value['id']."\');return false;";

    $objIcon->extra = 'onClick="'.$delValJS.'"';

    $deleteLink->link=$objIcon->show();
    $delShow=$this->objUser->isAdmin()? $deleteLink->show():"";
    $data .= "'".$delShow;

    $objIcon->setIcon('edit');
    $courseData = $this->objCourseProposals->getCourseProposal($value['id']);
    $facultyVal = $courseData['faculty'];
    $unitNameVal = $courseData['title'];
 
    $data .= "'";
    
    $data .= "]";
    if($cfac < $total) {
        $data .= ",";
    }

 $cfac++;
}

$faculty = $objLanguage->languageText('mod_ads_faculty','ads');
$unitName = $objLanguage->languageText('mod_ads_unitname','ads');
$submitUrl = $this->uri(array('action'=>'savecourseproposal'));
$cancelUrl = $this->uri(array('action'=>'NULL'));
$schoolUrl = $this->uri(array('action' => 'getSchools'));
$mainjs = "
                Ext.onReady(function(){
                Ext.QuickTips.init();
                var schools = [
                    $schoolData
                ];
                var faculties= [
                  $facultyData
                 ];
                var url='".str_replace("amp;", "", $submitUrl)."';
                var schoolurl='".str_replace("amp;", "", $schoolUrl)."';
                initAddProposal(schools,faculties,url,schoolurl);
                    
                    var xg = Ext.grid;
                    // shared reader
                    var reader = new Ext.data.ArrayReader({}, [
                       {name: 'title'},
                       {name: 'dateCreated'},
                       {name: 'owner'},
                       {name: 'status'},
                       {name: 'faculty'},
                        {name: 'edit'}
                    ]);

                    var grid = new xg.GridPanel({
                        store: new Ext.data.GroupingStore({
                            reader: reader,
                            data: xg.Data,
                            sortInfo:{field: 'title', direction: \"ASC\"},
                            groupField:'faculty'
                        }),

                        columns: [
                            {id:'".$title."',header: \"".$title."\", width: 200, dataIndex: 'title'},
                            {header: \"".$dateCreated."\", width: 120},
                            {header: \"".$owner."\", width: 120},
                            {header: \"".$status."\", width: 150, dataIndex: 'status'},
                            {header: \"".$faculty."\", width: 100, dataIndex: 'faculty'}
                            ";
                          if($this->objUser->isAdmin()) {
                            $mainjs .= ",{header: \"".$edit."\", width: 70, dataIndex: 'edit'}";
                           }
                          $mainjs .="
                        ],

                        view: new Ext.grid.GroupingView({
                            forceFit:true,
                            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Proposals\" : \"Proposal\"]})'
                        }),

                        frame:false,
                        width: 750,
                        height: 450,
                        collapsible: true,
                        animCollapse: false,
                        border:false,
                        renderTo: 'grouping-grid'
                    });
                });

      
                // Array data for the grids
               Ext.grid.Data = [".$data."];
                
               ";

$submitUrl = $this->uri(array('action'=>'savecourseproposal','edit'=>true,'id'=>$value['id']) );
$cancelUrl = $this->uri(array('action'=>'NULL'));


$btnListenerJS = "jQuery(document).ready(function() {
                   jQuery(\"#admin-btn\").click(function() {
                         window.location.href='".str_replace("amp;", "",$this->uri(array('action'=>'adminads','selectedtab'=>'0')))."'
                    });
              });";
echo "<script type=\"text/javascript\">".$btnListenerJS.$mainjs."</script>";

?>
