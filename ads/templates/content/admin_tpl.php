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
$adminjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/adminjs.js').'" type="text/javascript"></script>';
$commentsadminjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/commentsadmin.js').'" type="text/javascript"></script>';
$facultylistjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/facultylist.js').'" type="text/javascript"></script>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);
$this->appendArrayVar('headerParams',$styleSheet);

$this->appendArrayVar('headerParams',$adminjs);
$this->appendArrayVar('headerParams',$facultylistjs);
$this->appendArrayVar('headerParams',$commentsadminjs);

$facultyList = $this->objFaculty->getFacultyData();
$schoolList = $this->objSchool->getSchoolData();
$facultyModeratorList=$this->objFacultyModerator->getModeratorData();
$subFacultyModeratorList=$this->objSubFacultyModerator->getModeratorData();
$apoModeratorList=$this->objAPOModerator->getModeratorData();
// language stuff
$faculty = $this->objLanguage->languageText('mod_ads_faculty', 'ads');
$moderatorName = $this->objLanguage->languageText('mod_ads_moderatorname','ads');

$facultyData = "";
$count = 1;
$deleteFacultyLink=new link();
$total=count($facultyList);
foreach($facultyList as $data) {
   $deleteFacultyLink->link($this->uri(array('action'=>'deletefaculty','id'=>$data['id'])));
   $objIcon->setIcon('delete');
   $deleteFacultyLink->link=$objIcon->show();

    $facultyData.="['".$data['name']."','".$data['id']."','".$deleteFacultyLink->show()."']";
  
  if($count < $total){
      $facultyData.=",";
  }
   $count++;
}

$schoolData = "";
$count = 1;
$deleteSchoolLink=new link();
$total=count($schoolList);
foreach($schoolList as $data) {
   $deleteSchoolLink->link($this->uri(array('action'=>'deleteschool','school'=>$data['schoolname'], 'facultyname'=>$data['faculty'],'id'=>$data['id'])));
   $objIcon->setIcon('delete');
   $deleteSchoolLink->link=$objIcon->show();

    $schoolData.="['".$data['schoolname']."','".$data['faculty']."','".$deleteSchoolLink->show()."']";

  if($count < $total){
      $schoolData.=",";
  }
   $count++;
}

$facultyListing = $this->objFaculty->getAllFaculty();

$tmpFacultyData = "";
$count = 1;
$total=count($facultyListing);
foreach($facultyListing as $data) {
   $tmpFacultyData.="['".$data['name']."','".$data['id']."']";
  if($count < $total){
      $tmpFacultyData.=",";
  }
   $count++;
}

$facultyModeratorData = "";
$count = 1;
$deleteFacultyModeratorLink=new link();
$total=count($facultyModeratorList);
foreach($facultyModeratorList as $data) {
   $deleteFacultyModeratorLink->link($this->uri(array('action'=>'deletefacultymoderator','id'=>$data['id'])));
   $objIcon->setIcon('delete');
   $deleteFacultyModeratorLink->link=$objIcon->show();

    $facultyModeratorData.="['".$data['userid']."','".$this->objFaculty->getFacultyName($data['facultyid'])."','".$deleteFacultyModeratorLink->show()."']";

  if($count < $total){
      $facultyModeratorData.=",";
  }
   $count++;
}

$subFacultyModeratorData = "";
$count = 1;
$deleteSubFacultyModeratorLink=new link();
$total=count($subFacultyModeratorList);
foreach($subFacultyModeratorList as $data) {
   $deleteSubFacultyModeratorLink->link($this->uri(array('action'=>'deletesubfacultymoderator','id'=>$data['id'])));
   $objIcon->setIcon('delete');
   $deleteSubFacultyModeratorLink->link=$objIcon->show();

    $subFacultyModeratorData.="['".$data['userid']."','".$this->objFaculty->getFacultyName($data['facultyid'])."','".$deleteSubFacultyModeratorLink->show()."']";

  if($count < $total){
      $subFacultyModeratorData.=",";
  }
   $count++;
}

$apoModeratorData = "";
$count = 1;
$deleteAPOModeratorLink=new link();
$total=count($apoModeratorList);
foreach($apoModeratorList as $data) {
   $deleteAPOModeratorLink->link($this->uri(array('action'=>'deleteapomoderator','id'=>$data['id'])));
   $objIcon->setIcon('delete');

    $deleteAPOModeratorLink->link=$objIcon->show();

    $apoModeratorData.="['".$data['userid']."','".$this->objFaculty->getFacultyName($data['facultyid'])."','".$deleteAPOModeratorLink->show()."']";

  if($count < $total){
      $apoModeratorData.=",";
  }
   $count++;
}


$allcommentdata=$this->objCommentAdmin->getComments();
$commentData = "";
$count = 1;
$total=count($allcommentdata);
$deleteApoCommentExtraLink=new link();
foreach($allcommentdata as $data) {
    /*//get userid based on email
    $tmpID = $this->objDocumentStore->getUserId(trim($data['userid']));
    if(strlen(trim($tmpID)) == 0) {
        $moderator = "Not Available";
    }
    else {
        $moderator = $this->objUser->fullname($tmpID);
    }*/
    $moderator=$data['userid'];
    $deleteApoCommentExtraLink->link($this->uri(array('action'=>'deleteapoextracommenttype','id'=>$data['id'])));
    $objIcon->setIcon('delete');
    $deleteApoCommentExtraLink->link=$objIcon->show();
    $commentData.="['".$data['comment_desc']."','".$moderator."','".$data['id']."','".$deleteApoCommentExtraLink->show()."']";
    if($count < $total){
        $commentData.=",";
    }
    $count++;
}


$saveFacultyModeratorUrl = $this->uri(array('action'=>'savefacultymoderator'));
$saveSubFacultyModeratorUrl = $this->uri(array('action'=>'savesubfacultymoderator'));
$saveAPOModeratorUrl = $this->uri(array('action'=>'saveapomoderator'));
$saveFacultyUrl = $this->uri(array('action'=>'savefaculty'));
$saveSchoolUrl = $this->uri(array('action'=>'saveschool'));

$addFacultyButton = new button('addFaculty', 'Add Faculty');
$addFacultyButton->setId('addfaculty-btn');
$addFacultyButton->extra="style=\"margin-top: 2em;margin-bottom: 2em;\"";

$addSchoolButton= new button('addSchool', 'Add School');
$addSchoolButton->setId('addschool-btn');
$addSchoolButton->extra="style=\"margin-top: 2em;margin-bottom: 2em;\"";

$addFacultyModeratorButton = new button('addFacultyModerator', 'Add Faculty Moderator');
$addFacultyModeratorButton->setId('addfacultymoderator-btn');
$addFacultyModeratorButton->extra="style=\"margin-top: 2em;margin-bottom: 2em;\"";

$addSubFacultyModeratorButton = new button('addFacultyModerator', 'Add Moderator');
$addSubFacultyModeratorButton->setId('addsubfacultymoderator-btn');
$addSubFacultyModeratorButton->extra="style=\"margin-top: 2em;margin-bottom: 2em;\"";

$addAPOModeratorButton = new button('addAPOModerator', 'Add APO Moderator');
$addAPOModeratorButton->setId('addapomoderator-btn');
$addAPOModeratorButton->extra="style=\"margin-top: 2em;margin-bottom: 2em;\"";


$saveCommentUrl = new link($this->uri(array('action'=>'saveapoextracommenttype')));
$editCommentUrl = new link($this->uri(array('action'=>'updateapoextracommenttype')));
$saveEmailTemplateUrl = new link($this->uri(array('action'=>'saveemailtemplate')));

$addCommentButton = new button('addModerator', 'Add Custom Unit');
$addCommentButton->setId('addcomment-btn');
$addCommentButton->extra="style=\"margin-top: 2em;margin-bottom: 2em;\"";

$render='<div id="onecolumn">
                    <div id="content">
                    <div id="tabs" style="padding-left: 3em;"></div>

                    <div id="facultylist" style="padding-left: 3em;" class="x-hide-display">
                    <div id="addfaculty-win" class="x-hidden"><div class="x-window-header"></div></div>
                     '.$addFacultyButton->show().'
                    </div>


                    <div id="schoollist" style="padding-left: 3em;" class="x-hide-display">
                        <div id="addschool-win" class="x-hidden"><div class="x-window-header"></div></div>
                    '.$addSchoolButton->show().'
                    </div>


                    <div id="subfacultymoderators" style="padding-left: 3em;" class="x-hide-display">
                    <div id="addsubfacultymoderator-win" class="x-hidden"><div class="x-window-header"></div></div>
                    '.$addSubFacultyModeratorButton->show().'
                    </div>

                    <div id="facultymoderators" style="padding-left: 3em;" class="x-hide-display">
                    <div id="addfacultymoderator-win" class="x-hidden"><div class="x-window-header"></div></div>
                    '.$addFacultyModeratorButton->show().'
                    </div>

                    <div id="apomoderators" style="padding-left: 3em;" class="x-hide-display">
                    <div id="addapomoderator-win" class="x-hidden"><div class="x-window-header"></div></div>
                    '.$addAPOModeratorButton->show().'
                    </div>

                    <div id="commentlist" style="padding-left: 3em;" class="x-hide-display">
                    <div id="addcomment-win" class="x-hidden"><div class="x-window-header"></div></div>
                    '.$addCommentButton->show().'
                    </div>

                    <div id="emailtemplates" style="padding-left: 3em;" class="x-hide-display">
                     </div>
                    </div>';


echo $render;
$forwardtoworkmatetemplatecontent= $this->objEmailTemplates->getTemplateContent('forwardtoworkmate');
$forwardtoworkmatetemplatesubject= $this->objEmailTemplates->getTemplateSubject('forwardtoworkmate');

$forwardtoownertemplatecontent= $this->objEmailTemplates->getTemplateContent('forwardtoowner');
$forwardtoownertemplatesubject= $this->objEmailTemplates->getTemplateSubject('forwardtoowner');

$addmembertemplatecontent= $this->objEmailTemplates->getTemplateContent('addmember');
$addmembertemplatesubject= $this->objEmailTemplates->getTemplateSubject('addmember');

$addcommenttemplatecontent= $this->objEmailTemplates->getTemplateContent('addcomment');
$addcommenttemplatesubject= $this->objEmailTemplates->getTemplateSubject('addcomment');

$updatephasetemplatecontent= $this->objEmailTemplates->getTemplateContent('updatephase');
$updatephasetemplatesubject= $this->objEmailTemplates->getTemplateSubject('updatephase');

$mainjs = "/*!   * Ext JS Library 3.0.0
                 * Copyright(c) 2006-2009 Ext JS, LLC
                 * licensing@extjs.com
                 * http://www.extjs.com/license
                 */
                Ext.onReady(function(){

                 Ext.QuickTips.init();
                 showTabs('".$selectedtab."');

                var facultyListData=[".$facultyData."];
                showFacultyList(facultyListData,'".str_replace("amp;", "", $saveFacultyUrl)."');

                var facultySchoolData=[".$tmpFacultyData."];
                var schoolListData=[".$schoolData."];
                showSchoolList(facultySchoolData, schoolListData,'".str_replace("amp;", "", $saveSchoolUrl)."');

                var facultyModeratorListData=[".$facultyModeratorData."];
                showFacultyModeratorList(facultyModeratorListData,'".str_replace("amp;", "", $saveFacultyModeratorUrl)."',facultyListData);

                var subFacultyModeratorListData=[".$subFacultyModeratorData."];
                showSubFacultyModeratorList(subFacultyModeratorListData,'".str_replace("amp;", "", $saveSubFacultyModeratorUrl)."',facultyListData);

                var apoModeratorListData=[".$apoModeratorData."];
                showAPOModeratorList(apoModeratorListData,'".str_replace("amp;", "", $saveAPOModeratorUrl)."',facultyListData);

                 var contents=[
                '".$forwardtoworkmatetemplatesubject."',
                '".$forwardtoworkmatetemplatecontent."',

                '".$forwardtoownertemplatesubject."',
                '".$forwardtoownertemplatecontent."',

                 '".$addmembertemplatesubject."',
                 '".$addmembertemplatecontent."',

                 '".$addcommenttemplatesubject."',
                 '".$addcommenttemplatecontent."',

                 '".$updatephasetemplatesubject."',
                 '".$updatephasetemplatecontent."'
                 ];

                 var mData = [".$commentData."],
                 url = '".str_replace("amp;", "", $editCommentUrl->href)."',
                 url2 = '".str_replace("amp;", "", $saveCommentUrl->href)."';
                 url3 = '".str_replace("amp;", "", $saveEmailTemplateUrl->href)."';
                 showCommentAdmin(mData,url);
                 initCommentaddWin(url2);
                 initEmailTemplates(url3,contents);
                });
         ";
echo "<script type=\"text/javascript\">".$mainjs."</script>";
?>
