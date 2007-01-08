<?
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
$objTable  =& $this->newObject('htmltable','htmlelements');
$objLayer  =& $this->newObject('layer','htmlelements');
$objInput  =& $this->newObject('textinput','htmlelements');
$objDrop  =& $this->newObject('dropdown','htmlelements');
$objText  =& $this->newObject('textarea','htmlelements');
$objButton  =& $this->newObject('button','htmlelements');
$objForm  =& $this->newObject('form','htmlelements');
$objTimeOut =& $this->newObject('timeoutMessage','htmlelements');

// set up language items
$assignmenthead = $this->objLanguage->languageText('mod_assignmentadmin_assignment','assignmentadmin');
$btnupload = $this->objLanguage->languageText('mod_assignmentadmin_upload','Upload','assignmentadmin');
$markhead = $this->objLanguage->languageText('mod_assignmentadmin_mark','assignmentadmin');
$head = $markhead.' '.$assignmenthead;
$btnsubmit = $this->objLanguage->languageText('word_save','Save');
$btnexit = $this->objLanguage->languageText('word_exit','Exit');
$wordstudent = ucwords($this->objLanguage->languageText('mod_context_readonly'));
$fileLabel = $this->objLanguage->languageText('mod_assignmentadmin_filename');
$commenthead = $this->objLanguage->languageText('mod_assignmentadmin_comment');
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
$objTable->width = '99%';
$objTable->cellpadding = 4;
$objTable->startRow();
$objTable->addCell('<b>'.$wordstudent.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'
.$this->objUser->fullname($data[0]['userId']),'50%','','');
if(!$online){
    $objTable->addCell('<b>'.$fileLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$data[0]['fileName'],'50%','','');
}
$objTable->endRow();

$assignLabel = '<b>'.$assignmenthead.':</b>&nbsp;&nbsp;&nbsp;'.$data[0]['assignment'];
$objTable->addRow(array($assignLabel));
$objTable->addRow(array('&nbsp;'));

$objLayer->str = $objTable->show();
$str = $objLayer->show();

if(!$online){
    if(isset($msg)){
        $objTimeOut->setMessage($msg);
        $str .= '<p>'.$objTimeOut->show().'<p>';
    }

    // Display file input
    $objInput = new textinput('file');
    $objInput->fldType = 'file';

    $str .=  $objInput->show().'<p>';

    $objInput = new textinput('fileId',$data[0]['fileId']);
    $objInput->fldType = 'hidden';
    $str .=  $objInput->show();

    $objButton = new button('save',$btnupload);
    $objButton->setToSubmit();
    $btn = $objButton->show();

    $str .=  $btn.'<p>&nbsp;</p>';
}else{
    $objText = new textarea('online', $data[0]['online'],15,85);
    $objText->extra = 'wrap = soft, readonly';
    $str .=  '<b>'.$onlinehead.':<b>'.'<br />';
    $str .=  $objText->show().'<p>';
}

// Display mark & comment
$str .=  '<b>'.$markhead.' (%):<b>'.'<br />';

$objDrop = new dropdown('mark');
for($i=0; $i<=100; $i++){
    $objDrop->addOption($i, $i);
}
$objDrop->setSelected($data[0]['mark']);
$str .= $objDrop->show().'<p>';

$objText = new textarea('comment',$data[0]['comment'],5,60);
$objText->extra = 'wrap = soft';
$str .=  '<b>'.$commenthead.':<b>'.'<br />';
$str .=  $objText->show().'<p>';

// Hidden elements
$objInput = new textinput('submitId',$data[0]['id']);
$objInput->fldType = 'hidden';
$str .=  $objInput->show();

$objInput = new textinput('id',$data[0]['assignmentId']);
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

$str .=  $btns.'<p>';

/************************* set up form ******************************/

$objForm = new form('upload',$this->uri(array('action' =>'uploadsubmit')));
$objForm->extra = " ENCTYPE = 'multipart/form-data'";
$objForm->addToForm($str);
$objForm->addRule('mark', $errMark, 'required');

/************************* display page ******************************/
$objLayer->str = $objForm->show();
$objLayer->align = 'center';

echo $objLayer->show();

// exit form
$objForm = new form('exit', $this->uri(array('action' =>'uploadsubmit')));

$objInput = new textinput('save', $btnexit);
$objInput->fldType = 'hidden';
$objForm->addToForm($objInput->show());
$objInput = new textinput('id',$data[0]['assignmentId']);
$objInput->fldType = 'hidden';
$objForm->addToForm($objInput->show());
echo $objForm->show();
?>
