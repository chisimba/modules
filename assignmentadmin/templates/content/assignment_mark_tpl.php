<?php
/*
* Template for uploading assignments.
* @package assignmentadmin
*/

/**
* @param array $data Array containing the mark and comment for the assignment
* @param bool $online True if the assignment is an online assignment, FALSE if the assignment is uploadable.
*/

// set layout template
$this->setLayoutTemplate('assignmentadmin_layout_tpl.php');

// set up html elements
$this->loadClass('htmltable','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('form','htmlelements');
$objTimeOut = $this->newObject('timeoutMessage','htmlelements');

// set up language items
$assignmenthead = $this->objLanguage->languageText('mod_assignmentadmin_assignment','assignmentadmin');
$btnupload = $this->objLanguage->languageText('mod_assignmentadmin_upload','assignmentadmin');
$btndownload = $this->objLanguage->languageText('mod_assignmentadmin_download','assignmentadmin');
$markhead = $this->objLanguage->languageText('mod_assignmentadmin_mark','assignmentadmin');
$head = $markhead.' '.$assignmenthead;
$btnsubmit = $this->objLanguage->languageText('word_save');
$btnexit = $this->objLanguage->languageText('word_exit');
$wordstudent = ucwords($this->objLanguage->languageText('mod_context_readonly'));
$fileLabel = $this->objLanguage->languageText('mod_assignmentadmin_filename','assignmentadmin');
$commenthead = $this->objLanguage->languageText('mod_assignmentadmin_comment','assignmentadmin');
$onlinehead = $wordstudent.' '.$assignmenthead;

$errMark = $this->objLanguage->languageText('mod_assignmentadmin_entermark');

// javascript
$javascript = "<script language=\"javascript\" type=\"text/javascript\">
    function submitExitForm(){
        document.exit.submit();
    }
</script>";

echo $javascript;

/************************* set up table ******************************/

// header
$this->setVarByRef('heading',$head);
$str = '';
// Display name of student and name of file
$objTable = new htmltable();
$objTable->width = '99%';
$objTable->cellpadding = 4;
$objTable->startRow();
$objTable->addCell('<b>'.$wordstudent.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'
.$this->objUser->fullname($data[0]['userid']),'50%','','');
if(!$online){
    //$objTable->addCell('<b>'.$fileLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$data[0]['filename'],'50%','','');
}
$objTable->endRow();

$assignLabel = '<b>'.$assignmenthead.':</b>&nbsp;&nbsp;&nbsp;'.$data[0]['assignment'];
$objTable->addRow(array($assignLabel));
$objTable->addRow(array('&nbsp;'));

$objLayer = new layer();
$objLayer->str = $objTable->show();
$str = $objLayer->show();

//Download button 
$objButton = new button('submit',$btndownload);
//File selector class 
$objSelectFile = $this->newObject('selectfile','filemanager');
$objFile = $this->getObject('dbfile', 'filemanager');
$filepath .= $objFile->getFilePath($data[0]['studentfileid']);
$returnUrl = $filepath;
$fileId = $this->getParam('fileid');
$id = $this->getParam('id');
$submit_id = $this->getParam('submitId');
$returnUrl = $this->uri(array('action' => 'download','fileid'=>$data[0]['studentfileid'],'userid'=>$this->userId));
$objButton->setOnClick("window.location='$returnUrl'");

if(!$online){
$objLink = new link();
$objLink->link = 'Download';
$objLink->href = $filepath;
$btn1=  $objLink->show();
}

$str .= $btn1.'<br /><br />';

if(!$online){
    if(isset($msg)){
        $objTimeOut->setMessage($msg);
        $str .= '<p>'.$objTimeOut->show().'</p>';
    }

    $objInput = new textinput('fileId',$data[0]['lecturerfileid']);
    $objInput->fldType = 'hidden';
    $str .=  $objInput->show();		
    
}else{
   
	$objText = $this->newObject('htmlarea', 'htmlelements');
    $objText->init('online', $data[0]['online'], '500px', '500px');
    $objText->setDefaultToolBarSetWithoutSave();    
    $str .=  '<b>'.$onlinehead.':</b>'.'<br />';
    $str .=  $objText->show().'<p>';
}
// Display mark & comment
$str .=  '<b>'.$markhead.' (%):</b>'.'<br />';

$objDrop = new dropdown('mark');
for($i=0; $i<=100; $i++){
    $objDrop->addOption($i, $i);
}
$objDrop->setSelected($data[0]['mark']);
$str .= $objDrop->show().'</p>';

$objText = new textarea('comment',$data[0]['commentinfo'],5,60);
//$objText->extra = 'wrap = soft';
$str .=  '<b>'.$commenthead.':</b>'.'<br />';
$str .=  $objText->show().'<p>';

// Hidden elements
$objInput = new textinput('submitId',$data[0]['id']);
$objInput->fldType = 'hidden';
$str .=  $objInput->show();

$objInput = new textinput('id',$data[0]['assignmentid']);
$objInput->fldType = 'hidden';
$str .=  $objInput->show();

$objInput = new textinput('assignment',$data[0]['assignment']);
$objInput->fldType = 'hidden';
$str .=  $objInput->show();

// Save & exit buttons
$objButton = new button('save',$btnsubmit);
$objButton->setToSubmit();
$btns = $objButton->show().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$objButton = new button('save', $btnexit);
$objButton->setOnClick('javascript:submitExitForm()');
$btns .=  $objButton->show();

$str .=  $btns.'</p>';

/************************* set up form ******************************/


$fileId = $this->getParam('fileId');
$id = $this->getParam('id');
$submit_id = $this->getParam('submitId');

$objForm = new form('upload',$this->uri(array('action' =>'uploadsubmit', 'fileId'=>$fileId,'id'=>$id,'submitId'=>$submit_id)));
$objForm->extra = " enctype = 'multipart/form-data'";
$objForm->addToForm($str);
$objForm->addRule('mark', $errMark, 'required');

/************************* display page ******************************/
$objLayer = new layer();
$objLayer->str = $objForm->show();
$objLayer->align = 'center';

echo $objLayer->show();

// exit form
$objForm = new form('exit', $this->uri(array('action' =>'uploadsubmit')));

$objInput = new textinput('save', $btnexit);
$objInput->fldType = 'hidden';
$objForm->addToForm($objInput->show());
$objInput = new textinput('id',$data[0]['assignmentid']);
$objInput->fldType = 'hidden';
$objForm->addToForm($objInput->show());
echo $objForm->show();

?>
