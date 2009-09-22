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
$coursehistoryjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/proposaldetails.js').'" type="text/javascript"></script>';
$comboscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/combos.css').'"/>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);


$this->appendArrayVar('headerParams',$iconscss);
$this->appendArrayVar('headerParams',$coursehistoryjs);
$this->appendArrayVar('headerParams',$searchjs);
$this->appendArrayVar('headerParams',$searchfieldjs);
$this->appendArrayVar('headerParams',$gridsearchjs);
$this->appendArrayVar('headerParams',$comboscss);


$nextForm = new link($this->uri(array('action'=>'viewform','courseid'=>$this->id, 'formnumber'=>$this->allForms[0])));
$submitForm = new link($this->uri(array('action'=>'submitproposal','courseid'=>$this->id)));
$submitUrl = new link($this->uri(array('action'=>'sendProposal','edit'=>true,'id'=>$this->id)));
$addCommentUrl = new link($this->uri(array('action'=>'addcomment','courseid'=>$this->id)));
//$saveCommentUrl = new link($this->uri(array('action'=>'savecomment','courseid'=>$this->id)));

$editProposalTitleUrl = $this->uri(array('action'=>'updatecourseproposal','id'=>$this->id));
$searchusers =$this->uri(array('action'=>'searchusers'));

$ownerEmail=$this->objCourseProposals->getOwnerEmail($this->id);
$currentEditor=$this->objDocumentStore->getCurrentEditor($this->id);


$maxVersion=$this->objDocumentStore->getMaxVersion($this->id);
$lastEditDate=$this->objDocumentStore->getLastEditDate($this->id);
$status=$this->objDocumentStore->getStatus($this->id);
$iscurrentEdit=$this->objDocumentStore->isCurrentEditor($currentEditor);
$courseProposal=$this->objCourseProposals->getCourseProposal($this->id);
$saveCommentUrl = new link($this->uri(array('action'=>'savecomment','courseid'=>$this->id,'phase'=>$courseProposal['phase'])));
$homeUrl = $this->uri(array('action'=>'home'));
$sendProposalUrl = $this->uri(array('action'=>'sendproposal'));
$comments= $this->objComment->getAllcomments($this->id);
$grid = $this->objFaculty->getModeratorData();
$showCoursePropHistUrl = $this->uri(array('action'=>'showcourseprophist','courseid'=>$this->id,'selectedtab'=>'0'));

$objFaculty = $this->getObject('dbfaculty');
$facultyList = $objFaculty->getAllFaculty();


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

$items = "{boxLabel: 'New', name: 'proposalstatus', inputValue: '0'";
if($courseProposal['status'] == 0) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'APO comment', name: 'proposalstatus', inputValue: '1'";
if($courseProposal['status'] == 1) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'Library comment', name: 'proposalstatus', inputValue: '2'";
if($courseProposal['status']== 2) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'Subsidy comment', name: 'proposalstatus', inputValue: '3'";
if($courseProposal['status'] == 3) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'Faculty committee', name: 'proposalstatus', inputValue: '4'";
if($courseProposal['status'] == 4) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'Faculty', name: 'proposalstatus', inputValue: '5'";
if($courseProposal['status'] == 5) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'APDC', name: 'proposalstatus', inputValue: '6'";
if($courseProposal['status'] == 6) {
    $items .= ", checked: true";
}
$items .= "}";

$statuscodes=  array(
    "0"=> 'Proposal Phase',
    "1"=>'APO Comment',
    "2"=>'Faculty Approval'
   );
$mainjs=
    "
    var wform = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        width:750,
        labelWidth: 135,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:'".str_replace("amp;", "", $expressLink)."',
        defaultType: 'textfield',
        renderTo: 'surface',
        collapsible: true,
        defaults: {width: 750},
        bodyStyle:'background-color:transparent',
        border:false,
        items: {
            xtype: 'fieldset',
            title: 'Course proposal details',
            autoHeight: true,
            items:[";
   if($currentEditor == $this->objUser->email() &&
     $courseProposal['userid'] !=$this->objUser->userid() &&
     $courseProposal['phase'] == '0'
    ){
          $mainjs.=" new Ext.form.DisplayField({
               fieldLabel: '<b>State</b>',
               value: '<font color=\"red\"><b>This proposal was forwarded to \'".$currentEditor."\' by \'".$this->objUser->fullname($courseProposal['userid'])."\'</b></font>'
               }),";
     }
     if(
     $courseProposal['userid'] ==$this->objUser->userid() &&
     $currentEditor != $ownerEmail &&
     $courseProposal['phase'] == '0'
     ){
          $mainjs.=" new Ext.form.DisplayField({
               fieldLabel: '<b>State</b>',
               value: '<font color=\"red\"><b>You are the owner, but you have no priviledges. These rights are with ".$currentEditor."</b></font>'
               }),";
     }
           $mainjs.="new Ext.form.DisplayField({
               fieldLabel: 'Faculty',
               value: '".$objFaculty->getFacultyName($courseProposal['faculty'])."'
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Owner',
               value: '".$this->objUser->fullname($courseProposal['userid'])."'
               }),
               new Ext.form.DisplayField({
               fieldLabel: '<b>Current editor</b>',
               value: '<b>".$currentEditor."</b>'
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Last edit date',
               value: '".$lastEditDate."'
               }),
               new Ext.form.DisplayField({
               fieldLabel: '<b>Status</b>',
               value: '<b>".$statuscodes[$courseProposal['phase']]."</b>'
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Comments',
               value: '<b>".$comments."</b>'
               })
             ]
          }
    });

var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',

        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
         url:'".str_replace("amp;", "", $saveCommentUrl->href)."',
        defaultType: 'textfield',
        items:[
           new Ext.form.DisplayField({
               value: '<h2>Proposal Status:".$statuscodes[$courseProposal['phase']]."</h2>'
               }),
        new Ext.form.TextArea({
            name: 'commentField',
            id: 'commentsFieldId',
            width: 400,
            height: 200
          })
          ]

      });

   var radioGroup = {
   xtype: 'radiogroup',
   itemCls: 'x-check-group-alt',
   fieldLabel: 'Proposal Status',
   columns: 1,
   items: [$items]
    };

  var sform = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        url: 'index.php?module=ads&action=submitproposalstatus&id=".$courseProposal['id']."&version=".$maxVersion."',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        items:[
         radioGroup
          ]

      });


    var addCommentsWin;
    var searchUsersWin;
    var updateStatusWin;
    var button = Ext.get('back-btn');
    button.on('click', function(){
     window.location.href = '".str_replace("amp;", "",$homeUrl)."';
    });

   
function processActionDD(){
 var actiondd = document.getElementById('actiondd').value;

  if(actiondd == \"addcomment\"){
       if(!addCommentsWin){
            addCommentsWin = new Ext.Window({
                applyTo:'addcomments-win',
                layout:'fit',
                title:'Enter Comments',
                width:500,
                height:300,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,
                items: [
                 form
                ],
                  buttons: [{
                    text:'Save',
                    handler: function(){
                  if (form.url){
                            form.getForm().getEl().dom.action = form.url;
                          }
                        form.getForm().submit();

                  }
                  }
                  ,{
                    text: 'Cancel',
                    handler: function(){
                       addCommentsWin.hide();
                        window.location.href = '".str_replace("amp;", "",$showCoursePropHistUrl)."';
                    }
                  }
                ]

            });
        }

        addCommentsWin.show(this);
      }

    if(actiondd == \"updatestatus\"){

       if(!updateStatusWin){
            updateStatusWin = new Ext.Window({
                applyTo:'status-win',
                layout:'fit',
                title:'Update Status',
                width:300,
                height:300,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,
                items: [
                sform
                ],
                  buttons: [{
                    text:'Save',
                    handler: function(){
                  if (sform.url){
                            sform.getForm().getEl().dom.action = sform.url;
                          }
                        sform.getForm().submit();

                  }
                  }
                  ,{
                    text: 'Cancel',
                    handler: function(){
                       updateStatusWin.hide();
                        window.location.href = '".str_replace("amp;", "",$showCoursePropHistUrl)."';
                    }
                  }
                ]

            });
        }
        updateStatusWin.show(this);
      }//end if

    if(actiondd == \"editproposal\"){
      window.location.href = '".str_replace("amp;", "",$nextForm->href)."';
    }//end if

if(actiondd == \"forward\"){
showSearchWinX('".$this->id."','".$sendProposalUrl."','Forward','forwardProposal','out-search-xwin','".str_replace("amp;", "", $searchusers)."');
}//end if


if(actiondd == \"forwardtomoderator\"){
//showSearchWinX('".$this->id."','".$sendProposalUrl."','Forward','forwardProposalToModerator','search-xwin','".str_replace("amp;", "", $searchusers)."');
forwardProposalToModerator($grid,'$this->id');
}//end if

if(actiondd == \"forwardforapocomment\"){
forwardForAPOComment('".$this->id."','".$maxVersion."');
}
if(actiondd == \"forwardtoowner\"){
forwardToOwner('".$this->id."','".$ownerEmail."');
}
if(actiondd == \"forwardtoownerfromapo\"){
forwardToOwnerFromAPO('".$this->id."','".$ownerEmail."');
}

if(actiondd == \"changetitle\"){

var faculties= [
$facultyData
               ];
var url='".str_replace("amp;", "", $editProposalTitleUrl)."';
showEditProposalWin(faculties,url,'".$objFaculty->getFacultyName($courseProposal['faculty'])."','".$courseProposal['title']."');
}
};


Ext.onReady(function(){
    var historyURL = '".str_replace("amp;", "",$this->uri(array('action'=>'gethistorydata', 'courseid'=>$this->id, 'formnumber'=>$this->allforms[0])))."';
";
  if($alert){
      $mainjs.="  Ext.MessageBox.alert('Status', '".$alert."');";
  }
  $proposalMembersData=$this->objProposalMembers->getMembers($this->id);
  $deleteMemberLink=new link();
  $propData="";
  $membercount=count($proposalMembersData);
  $mcount=0;
  $deleteLink=new link();

  if($membercount > 0){

  foreach($proposalMembersData as $row){
      $deleteLink->link($this->uri(array('action'=>'deleteproposalmember','id'=>$row['id'],'courseid'=>$this->id)));
      $objIcon->setIcon('delete');
      $deleteLink->link=$objIcon->show();
      $propData.="['".$this->objUser->fullname($row['userid'])."','".$this->objUser->email($row['userid'])."','".$deleteLink->show()."']";
      $mcount++;
      if($mcount < $membercount){
          $propData.=",";
      }
  }
  }




$content.= "<script type=\"text/javascript\">".$mainjs."</script>";

$actionsDropDown = new dropdown ('actions');
$actionsDropDown->cssId = 'actiondd';

$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$modemail=$objSysConfig->getValue('EMAIL_MODERATOR', 'ads');

$actionsDropDown->addOption('default','Select action ...');
 $showMembers= "false";
//case 1: owner is current editor in phase 0 and is logged in
 
 if($ownerEmail == $this->objUser->email() &&
     $courseProposal['phase'] == 0 &&
     $currentEditor == $this->objUser->email()
  ){
  $actionsDropDown->addOption('editproposal','Edit Proposal');
  $actionsDropDown->addOption('forward','Forward to workmate');
  $actionsDropDown->addOption('forwardforapocomment','Forward for APO Comments');
  $actionsDropDown->addOption('changetitle','Change Title/Faculty');
  $showMembers="true";
}
 
//case  current editor not ower, logged in. phase 0 
 if($currentEditor != $this->objUser->email() &&
     $ownerEmail != $this->objUser->email() &&
     $courseProposal['phase'] == 0){

   $actionsDropDown->addOption('editproposal','Edit Proposal');
   $actionsDropDown->addOption('forwardtoowner','Forward to owner');
   $showMembers="false";
 }


 

 if($currentEditor == $this->objUser->email() && $courseProposal['phase'] == 1){
  $actionsDropDown->addOption('editproposal','Edit Proposal');
  $actionsDropDown->addOption('addcomment','Add Comment');
  $actionsDropDown->addOption('forward','Forward for comments');
  $actionsDropDown->addOption('forwardforfacultyapproval','Forward for Faculty Approval');
  $actionsDropDown->addOption('forwardtoownerfromapo','Forward to owner');
}


 $mainjs.="addTree('".$this->id."', historyURL);
    showTabs('".$selectedtab."','".$showMembers."');
    var mData = [".$propData."];
    showProposalMembers(mData);
});
";
$backButton = new button('back','Back');
$backButton->setId('back-btn');
$content = $message;
$renderSurface='
        <div id="addcomments-win" class="x-hidden">
        <div class="x-window-header">Add Comment</div>
        </div>

        <div id="status-win" class="x-hidden">
        <div class="x-window-header">Update Status</div>
        </div>

        <div id="search-xwin" class="x-hidden">
        <div class="x-window-header">Search</div>
        </div>
        <div id="fowardwin" class="x-hidden">
        <div class="x-window-header">Forward To Moderator</div>
        </div>

';
$content= '<div id="surface"><h1>'.$courseProposal['title'].'</h1>'.$renderSurface.'   </div>';
$content.= "<script type=\"text/javascript\">".$mainjs."</script>";


$actionsDropDown->addOnChange('processActionDD();');
$renderContent='<div>'.$actionsDropDown->show().'<br/>'.$backButton->show().'<br/>'.$content.'</div>';

$addMemberButton = new button('addmember','Add New Member');
$addMemberButton->setId('add-member-btn');
$addMemberButton->extra='style="padding-left: 3em;"';
$searchFieldBody=
'

 <div id="memberssurface"></div>
    
';
$render='<div id="onecolumn">
                    <div id="out-search-xwin" class="x-hidden">
                    <div class="x-window-header">Search</div>
                    </div>
                    <div id="content">
                    <div id="tabs" style="padding-left: 3em;"></div>
                    <div id="tree-div" style="padding-top: 2em;"  class="x-hide-display"></div>
                    <div id="contentcontent"  class="x-hide-display">
                    '.$renderContent.'
                    </div>
                    <div style="padding-left: 3em;">'.$vsearchFieldBody.'</div>
                    <div id="memberscontent" class="x-hide-display">&nbsp;'.$addMemberButton->show().'<br/>'.$searchFieldBody.'<br/></div>
                    </div>
                    </div>

<input type="hidden" name="homeURL" value = "'.str_replace("amp;", "",$this->uri(array())).'">';
$addMemberJS = "jQuery(document).ready(function() {
                    

                    jQuery(\"#add-member-btn\").click(function() {
                        showSearchWinX('".$this->id."','".$sendProposalUrl."','Add','addProposalMember','memberssurface','".str_replace("amp;", "", $searchusers)."');
                    });
              });";

echo "<script type='text/javascript'>".$addMemberJS."</script>";
echo $render;
?>