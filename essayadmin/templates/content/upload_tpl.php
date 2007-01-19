<?php
/*
* Template for uploading essays.
* @package essayadmin
*/

/**
* @param array $data Array containing the mark and comment for the essay
*/

$styleSheet=0;
$styleSheet="
<style type=\"text/css\">	
.form_widget_amount_slider{
	border-top:1px solid #9d9c99;
	border-left:1px solid #9d9c99;
	border-bottom:1px solid #eee;
	border-right:1px solid #eee;
	background-color:#f0ede0;
	height:3px;
	position:absolute;
	bottom:0px;
}
</style>
";
echo $styleSheet;

//javascript for the slider
$str=0;
$str='<script language="JavaScript" src="modules/essayadmin/resources/dhtmlslider.js" type="text/JavaScript"></script>';
//append to the top of the page
$this->appendArrayVar('headerParams', $str);

// set layout template
$this->setLayoutTemplate('essayadmin_layout_tpl.php');

// set up html elements
$objTable = new htmltable();
$objSubTable = new htmltable();
$objSubTable->width="60%";
$objLayer=$this->objLayer;
$objConfirm =& $this->newObject('timeoutmessage','htmlelements');
$objDrop =& $this->newObject('dropdown','htmlelements');
$objTextinput =& $this->newObject('textinput','htmlelements');

// set up language items
$essayhead=$this->objLanguage->languageText('mod_essayadmin_essay','essayadmin');
$btnupload=$this->objLanguage->languageText('mod_essayadmin_upload','essayadmin');
$uploadhead=$btnupload.' '.$essayhead;
$head=$uploadhead;
$markshead=' '.$this->objLanguage->languageText('mod_essayadmin_marks','essayadmin');
$btnsubmit=$this->objLanguage->languageText('word_save');
$btnexit=$this->objLanguage->languageText('word_exit');
$wordstudent=ucwords($this->objLanguage->languageText('mod_context_readonly','essayadmin'));
$markhead=$this->objLanguage->languageText('mod_essayadmin_mark','essayadmin').' (%)';
$commenthead=$this->objLanguage->languageText('mod_essayadmin_comment','essayadmin');
$rubrichead=$this->objLanguage->languageText('mod_essayadmin_use','essayadmin').' '.$this->objLanguage->languageText('rubric_rubric');

$errMark = $this->objLanguage->languageText('mod_essayadmin_entermark','essayadmin');

$head.=$markshead;

/**
* new language items added 5/apr/06
* @author: otim samuel, sotim@dicts.mak.ac.ug
*/
$downloadEssay=0;
$downloadEssay=$this->objLanguage->languageText('mod_essayadmin_downloadessay','essayadmin');
$dateSubmitted=0;
$dateSubmitted=$this->objLanguage->languageText('mod_essayadmin_submitted','essayadmin');
$dateSubmittedLate=0;
$dateSubmittedLate=$this->objLanguage->languageText('mod_essayadmin_submittedlate','essayadmin');

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

// get booked essays in topic
$data=$this->dbbook->getBooking("where id='$book'");
//get closing_date for topic
$topicdata=array();
$topicdata=$this->dbtopic->getTopic($data[0]['topicid']);

// get essay title
$essay=$this->dbessays->getEssay($data[0]['essayid'],'topic');
$essaytitle=$essay[0]['topic'];
$rubric='';

// display area insert essay mark and comment
// get data
// get topic id
$topic=$this->getParam('id');
// get rubric id
$rubric=$this->getParam('rubric');

// get student name
$studentid=$data[0]['studentid'];
$studentname=$this->objUser->fullname($studentid);
//download link for the student's submitted essay
$this->objLink=new link($this->uri(array('action'=>'download','fileid'=>$data[0]['studentfileid'])));
$this->objLink->link=$downloadEssay;
$downloadEssayLink=0;
$downloadEssayLink=$this->objLink->show();
//is the submitted date later than the closing date?
$closingDate=0;
$closingDate=$topicdata[0]['closing_date'];
$isLate=0;
$isLate = $this->objDateformat->getDateDifference($closingDate,$data[0]['submitdate']);

// display student name and essay title
$objTable->startRow();
$objTable->addCell('','','','','even');
$objTable->addCell('<b>'.$wordstudent.':<b>&nbsp;&nbsp;&nbsp;&nbsp;'.$studentname.'<br>'.($isLate?'<font color=\'red\'><strong>'.$dateSubmittedLate.':</strong>&nbsp;&nbsp;&nbsp;'.$this->objDateformat->formatDate($data[0]['submitdate']).'</font>':'<strong>'.$dateSubmitted.':</strong>&nbsp;&nbsp;&nbsp;'.$this->objDateformat->formatDate($data[0]['submitdate'])).'<br>'.$downloadEssayLink,'','','center','even');
$objTable->addCell('<b>'.$essayhead.':<b>&nbsp;&nbsp;&nbsp;&nbsp;'.$essaytitle,'','','center','even');
$objTable->addCell('','','','','even');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

if(!empty($rubric)){
    $objTable->startRow();
    $objTable->addCell('');
    $objTable->addCell('<b>'.$rubrichead.'<b>','','','center','',' colspan="2"');
    $objTable->endRow();

    // iframe containing rubric
    $this->objIframe->iframe();
    $this->objIframe->name='rubric';
    $this->objIframe->id='rubric';
    $this->objIframe->width='500';
    $this->objIframe->height='230';
    $this->objIframe->frameborder=0;
    $this->objIframe->scrolling=1;
    $this->objIframe->src=$this->uri(array('action'=>'usetable','tableId'=>$rubric,'NoBanner'=>'yes','studentNo'=>$studentid,'student'=>$studentname),'rubric');

    $objTable->startRow();
    $objTable->addCell('');
    $objTable->addCell($this->objIframe->show(),'','','center','',' colspan="2"');
    $objTable->endRow();

    $objTable->row_attributes=' height="15"';
    $objTable->startRow();
    $objTable->addCell('');
    $objTable->endRow();
}

/**
//Insert mark
$objDrop = new dropdown('mark');
for($i=0; $i<=100; $i++){
    $objDrop->addOption($i, $i);
}
$objDrop->setSelected($data[0]['mark']);
*/

//Insert mark
$objTextinput = new textinput('mark','0');
$objTextinput->size='5';
$objTextinput->extra=' maxlength=\'3\'';

$objSubTable->startRow();
$objSubTable->addCell('&nbsp;','60%','','right','',' id=\'slider_target\'');
$objSubTable->addCell($objTextinput->show().' %','40%','','center');
$objSubTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell('<b>'.$markhead.'<b>','','','center','',' colspan="2"');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($objSubTable->show(),'','','center','',' colspan="2"');
$objTable->endRow();

$objTable->row_attributes=' height="15"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// add a comment
$this->objText = new textarea('comment',$data[0]['comment'],5,60);
$this->objText->extra='wrap=soft';

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell('<b>'.$commenthead.'<b>','','','center','',' colspan="2"');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($this->objText->show(),'','','center','',' colspan="2"');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// hidden input: topic id
$this->objInput = new textinput('id',$topic);
$this->objInput->fldType='hidden';
$hidden = $this->objInput->show();

// save mark and comment button
$this->objButton = new button('save',$btnsubmit);
$this->objButton->setToSubmit();
$btn3=$this->objButton->show();

$this->objButton = new button('save', $btnexit);
$this->objButton->setOnClick('javascript:submitExitForm()');
$btn2 =$this->objButton->show();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($btn3.'&nbsp;&nbsp;&nbsp;','30%','','right');
$objTable->addCell('&nbsp;&nbsp;&nbsp;'.$btn2,'30%','','left');
$objTable->endRow();

// display confirmation message
if(!empty($msg)){
    $objConfirm->setMessage($msg);
    $confirmMsg = $objConfirm->show();
}else{
    $confirmMsg = '';
}

$objTable->row_attributes=' height="40"';
$objTable->startRow();
$objTable->addCell('','20%');
$objTable->addCell($confirmMsg,'60%','','center','',' colspan="2"');
$objTable->addCell('','20%');
$objTable->endRow();

$objTable->row_attributes='';
$objTable->startRow();
$objTable->addCell('<b>'.$uploadhead.'<b>','','','center','even',' colspan="4"');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// file input
$this->objInput = new textinput('file');
$this->objInput->fldType='file';
$this->objInput->size='';

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($this->objInput->show(),'','','center','',' colspan="2"');
$objTable->endRow();

$objTable->row_attributes=' height="30"';
$objTable->startRow();
$objTable->addCell($hidden);
$objTable->endRow();

// submit and exit buttons
$this->objButton = new button('save',$btnupload);
$this->objButton->setToSubmit();
$btn1=$this->objButton->show();

$objTable->startRow();
$objTable->addCell('');
$objTable->addCell($btn1,'','','right');
$objTable->addCell('','','','left');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

/************************* set up form ******************************/

$this->objForm = new form('upload',$this->uri(array('action'=>'uploadsubmit','book'=>$book)));
$this->objForm->extra=" ENCTYPE='multipart/form-data'";
$this->objForm->addToForm($objTable->show());
$this->objForm->addRule('mark', $errMark, 'required');

/************************* display page ******************************/
echo $this->objForm->show();

//last portion of the slider's script
$slider=0;
$slider="
<script type=\"text/javascript\">
form_widget_amount_slider('slider_target',document.upload.mark,200,0,100,\"\");
</script>
";
echo $slider;

// exit form
$this->objForm = new form('exit', $this->uri(array('action'=>'uploadsubmit','book'=>$book)));

//hidden input: topic id
$this->objInput = new textinput('id',$topic);
$this->objInput->fldType='hidden';
$this->objForm->addToForm($this->objInput->show());

$this->objInput = new textinput('save',$btnexit);
$this->objInput->fldType='hidden';
$this->objForm->addToForm($this->objInput->show());

echo $this->objForm->show();
?>