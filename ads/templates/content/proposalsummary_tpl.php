<?php
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');

$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/session.css').'"/>';

$iconscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/icons.css').'"/>';
$searchfieldjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/SearchField.js').'" type="text/javascript"></script>';
$gridsearchjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/LiveSearch.js').'" type="text/javascript"></script>';
$proposalsummaryjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/proposalsummary.js').'" type="text/javascript"></script>';
$comboscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/combos.css').'"/>';
$buttonscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css','ads').'"/>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);


$this->appendArrayVar('headerParams',$iconscss);
$this->appendArrayVar('headerParams',$proposalsummaryjs);
$this->appendArrayVar('headerParams',$searchjs);
$this->appendArrayVar('headerParams',$searchfieldjs);
$this->appendArrayVar('headerParams',$gridsearchjs);
$this->appendArrayVar('headerParams',$comboscss);
$this->appendArrayVar('headerParams',$buttonscss);
$courseProposal=$this->objCourseProposals->getCourseProposal($this->id);
/**
 *
 * URLS
 *
 */
$addUnitCommentUrl = new link($this->uri(array('action'=>'addunitcommentor','courseid'=>$this->id,'phase'=>$courseProposal['phase'])));
$sendProposalUrl = $this->uri(array('action'=>'sendproposal'));
$searchusers =$this->uri(array('action'=>'searchusers'));
$editTitleLink = new link("#");
$editProposalTitleUrl = $this->uri(array('action'=>'updatecourseproposal','id'=>$this->id));
$saveCommentUrl = new link($this->uri(array('action'=>'savecomment','courseid'=>$this->id,'phase'=>$courseProposal['phase'])));
$schoolUrl = $this->uri(array('action' => 'getSchools'));
/**
 * Formatted data from db
 *
 */

//faculties
$objFaculty = $this->getObject('dbfaculty');
$facultyList = $objFaculty->getAllFaculty();
$schoolList = $this->objSchool->getSchoolData();
$historyData = $this->objCourseProposals->getHistoryData($this->id);

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

//proposal meta info
$faculty=$this->objFaculty->getFacultyName($courseProposal['faculty']);
$school = $this->objSchool->getSchoolName($courseProposal['school']);
$owner=$this->objUser->fullname($courseProposal['userid']);
$currentEditor=$this->objDocumentStore->getCurrentEditor($this->id);
$ownerEmail=$this->objCourseProposals->getOwnerEmail($this->id);
$lastEditDate=$this->objDocumentStore->getLastEditDate($this->id);
$maxVersion=$this->objDocumentStore->getMaxVersion($this->id);
$statuscodes=  array(
    "0"=> 'Proposal Phase',
    "1"=>'APO Comment',
    "2"=>'Faculty Subcommittee Approval',
    "3"=>'Faculty Board Approval'
   );
$phase=$statuscodes[$courseProposal['phase']];
$comments= $this->objComment->getAllcomments($this->id);

//unit comments
$unitComments=$this->objProposalMembers->getUnitCommentors($this->id,$courseProposal['phase']);
$count = 1;
$forwardData="";
$total=count($unitComments);
foreach($unitComments as $data) {
  $forwardData.="['".$this->objCommentAdmin->getCommentType($data['unit_type']).

  "','".$this->objUser->fullname($data['userid'])."']";
    if($count < $total){
      $forwardData.=",";
    }
   $count++;
}

//type of unit comments
$commentTypeData=$this->objCommentAdmin->getComments();
$count = 1;
$commentData="";
$total=count($commentTypeData);
foreach($commentTypeData as $data) {
  $commentData.="['".$data['comment_desc']."','".$data['id']."']";
    if($count < $total){
      $commentData.=",";
    }
   $count++;
}

  //comment editors
  $proposalMembersData=$this->objProposalMembers->getMembers($this->id,$courseProposal['phase']);
  $deleteMemberLink=new link();
  $propData="";
  $hisData = "";
  $membercount=count($proposalMembersData);
  $mcount=0;
  $deleteLink=new link();

  if($membercount > 0){

  foreach($proposalMembersData as $row){
      $deleteLink->link($this->uri(array('action'=>'deleteproposalmember','id'=>$row['id'],'courseid'=>$this->id)));
      $objIcon->setIcon('delete');
      $deleteLink->link=$objIcon->show();
      $showDeleteLink=$currentEditor == $this->objUser->email()?$deleteLink->show():"";
      $propData.="['".$this->objUser->fullname($row['userid'])."','".$this->objUser->email($row['userid'])."','".$showDeleteLink."']";
      $mcount++;
      if($mcount < $membercount){
          $propData.=",";
      }
  }
  }

  //history data grid
  $hisData = $this->objDocumenHistory->getData($this->id);

if(!$selectedtab){
    $selectedtab="0";
}
if($alert){
      $mainjs.="  Ext.MessageBox.alert('Status', '".$alert."');";
}

$disableCommentsButton=($currentEditor == $this->objUser->email() || 
    $this->objProposalMembers->isMember($this->id,$this->objUser->userid()) ||
    $this->objUser->isAdmin()
    )?'false': 'true';




$disablePhaseForwardButton='true';
$disableForwardButton='true';
$forwardText="Forward";
$forwardPhaseText="Forward";
$forwardactions="";

 if($currentEditor == $this->objUser->email() &&
     $ownerEmail == $this->objUser->email() &&
     $courseProposal['phase'] == 0){
     $forwardText='Transfer to workmate';
     $disablePhaseForwardButton='false';
     $forwardPhaseText="Forward";
     $forwarddata="'".$this->id."','".$maxVersion."','".$ownerEmail."'";
     $forwardactions="['0','Forward to APO'],['1','Forward to workmate']";
 }

 if($currentEditor == $this->objUser->email() &&
     $ownerEmail != $this->objUser->email() &&
     $courseProposal['phase'] == 0){
     $forwardText='Transfer to owner';
     $forwarddata="'".$this->id."','".$ownerEmail."','".$ownerEmail."'";
     $disablePhaseForwardButton='false';
     $forwardactions="['1','Forward to owner']";
     $forwardPhaseText="Forward";
 }

 if($currentEditor == $this->objUser->email() &&
     $ownerEmail != $this->objUser->email() &&
     $courseProposal['phase'] == 1){
     $forwardPhaseText='Forward';
     $forwarddata="'".$this->id."','".$maxVersion."','".$ownerEmail."'";
     $disablePhaseForwardButton='false';
     $forwardactions="['0','Forward to Faculty subcommittee'],['1','Forward to owner']";
 }

  if(($currentEditor == $this->objUser->email() &&
     $ownerEmail != $this->objUser->email() || $this->objUser->isAdmin()) &&
     $courseProposal['phase'] == 2){
     $forwardPhaseText='Forward';
     $forwarddata="'".$this->id."','".$maxVersion."','".$ownerEmail."'";
     $disablePhaseForwardButton='false';
     $forwardactions="['0','Forward to Faculty board'],['1','Forward to APO'],['2','Forward to owner']";
  }
  if(($currentEditor == $this->objUser->email() &&
     $ownerEmail != $this->objUser->email() || $this->objUser->isAdmin()) &&
     $courseProposal['phase'] == 3){
     $forwardPhaseText='Forward';
     $forwarddata="'".$this->id."','".$maxVersion."','".$ownerEmail."'";
     $disablePhaseForwardButton='false';
     $forwardactions="['0','Forward to Faculty subcommittee'],['1','Forward to APO'],['2','Forward to owner']";
  }
if($this->objUser->isadmin()){
$disablePhaseForwardButton='false';
$forwardPhaseText='Forward';
     $forwarddata="'".$this->id."','".$maxVersion."','".$ownerEmail."'";
     $disablePhaseForwardButton='false';
     $forwardactions="['0','Forward to Faculty subcommittee'],['1','Forward to APO'],['2','Forward to owner'],['3','Forward to Faculty board']";
}

$searchdata="'".$this->id."','".str_replace("amp;", "", $sendProposalUrl)."','Send to','forwardProposal','usersearch-surface','".str_replace("amp;", "", $searchusers)."','".$courseProposal['phase']."'";

$mainjs="

Ext.onReady(function(){
var summaryData=['".$faculty."','".$school."','".$owner."','".$currentEditor."','".$lastEditDate."','".$phase."'];

var comments='".$comments."';
var phaseTitle='".$phase."';
var commentsSaveUrl='".str_replace("amp;", "", $saveCommentUrl->href)."';
var disableAddCommentsButton=".$disableCommentsButton.";
var disableForwardButton=".$disableForwardButton.";
var forwardText='".$forwardText."';
var disablePhaseForwardButton=".$disablePhaseForwardButton.";
var forwardPhaseText='$forwardPhaseText';
var searchdata=[".$searchdata."];
var forwarddata=[".$forwarddata."];
var forwardactions=[$forwardactions];
showSummary(
summaryData,
comments,
commentsSaveUrl,
phaseTitle,
disableAddCommentsButton,
disableForwardButton,
forwardText,
disablePhaseForwardButton,
forwardPhaseText,
searchdata,
forwarddata,
forwardactions
);

var mData = [".$propData."];
var hData = [".$hisData."];
showProposalMembers(mData);
showHistory(hData);
var forwardData=[".$forwardData."];
showUnitCommentEditors(forwardData);
var tabs = new Ext.TabPanel({
        renderTo: 'tabs',
        width:750,
        activeTab: 0,
        frame:true,
        activeTab: parseInt(".$selectedtab."),
        defaults:{autoHeight: true},
        items:[
            {contentEl:'summary', title: 'Summary'},
            {contentEl:'commenteditors', title: 'Comment Editors'},
            {contentEl:'unitcommenteditors', title: 'Faculty/Department Comment Editors'},
            {contentEl:'historyGrid', title:'History'}
        ]
    });

});
";
echo "<script type=\"text/javascript\">".$mainjs."</script>";

/**
 * Buttons
 */
$addForwardButton = new button('addunitmember','Add');
$addForwardButton->setId('forwardforextraapocomments');
$addForwardButton->extra='style="margin-left: 1em;margin-top: 2em;"';

$showAddForwardButton=$currentEditor == $this->objUser->email() ? $addForwardButton->show(): "";


$addMemberButton = new button('addmember','Add');
$addMemberButton->setId('add-member-btn');
$addMemberButton->extra='style="margin-left: 1em;margin-top: 2em;"';
$showAddMemberButton=$currentEditor == $this->objUser->email() ||
$ownerEmail == $this->objUser->email() ?$addMemberButton->show():"";

$editable=$currentEditor == $this->objUser->email() ? 'true':'false';
$nav = $this->getObject('nav', 'ads');
$leftContent= $nav->getLeftContent('A', 'viewform', $this->id,$editable);

$objIcon->setIcon('edit');
$editTitleLink->link=$objIcon->show();
//showEditProposalWin(faculties,url,selectedFaculty,proposalName)
$editVars="
<script type=\"text/javascript\">
var editFaculties=[".$facultyData."];
var editSchools=[$schoolData];
var editFacultyurl='".str_replace("amp;", "", $editProposalTitleUrl)."';
var selectedFaculty='".$objFaculty->getFacultyName($courseProposal['faculty'])."';
var selectedSchool='".$this->objSchool->getSchoolName($courseProposal['school'])."';
var editProposalName='".$courseProposal['title']."';
var schoolurl='".str_replace("amp;", "", $schoolUrl)."';
</script>
";
echo $editVars;

$editValJS="showEditProposalWin(editFaculties,editSchools,editFacultyurl,selectedFaculty,selectedSchool,editProposalName, schoolurl);return false;";
$editTitleLink->extra='onClick="'.$editValJS.'"';
$showEditTitleLink=$ownerEmail == $this->objUser->email() ? $editTitleLink->show():"";


$middleContent='
<h4>Welcome  '.$this->objUser->fullname().'</h4>
<h1>'.$courseProposal['title'].$showEditTitleLink.'</h1>
<font color="red"><h2>Phase:'.$statuscodes[$courseProposal['phase']].'</h2></font>
<div id="message" style="color:red;font-weight:bold;text-decoration:underline">'.$this->getParam('errormessage').'</div>
<div id="usersearch-surface"></div>
<div id="popup-surface"></div>
<div id="tabs">
        <div id="summary" class="x-hide-display style="margin-left: 2em;margin-top: 2em;">
            <p>
           
            </p>
        </div>
        <div id="historyGrid"  class="x-hide-display"></div>
        <div id="commenteditors" class="x-hide-display">
          '.$showAddMemberButton.'
            <p></p>
        </div>

        <div id="unitcommenteditors" class="x-hide-display">
           '.$showAddForwardButton.'
            <p></p>
        </div>
    </div>
';
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent($leftContent);
$cssLayout->setMiddleColumnContent($middleContent);
echo $cssLayout->show();

$buttonListener = "jQuery(document).ready(function() {
                    jQuery(\"#add-member-btn\").click(function() {
                        showSearchWinX('".$this->id."','".$sendProposalUrl."','Add','addProposalMember','popup-surface','".str_replace("amp;", "", $searchusers)."','".$courseProposal['phase']."');
                    });
                    jQuery(\"#forwardforextraapocomments\").click(function() {
                       var commentD=[$commentData];
                       var url='".str_replace("amp;", "", $addUnitCommentUrl->href)."';
                       showForwardForCommentsWin(commentD,url);
                    });
              });";

echo "<script type='text/javascript'>".$buttonListener."</script>";
?>
