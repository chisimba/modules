<?
/**
* @package pbladmin
*/

/*
* Template for PBL Admin: Page to add/edit the students in a class.
* @param array $users The users in the student group for the context.
* @param array $students The students in the pbl class.
* @param string $id The id of the Class.
* @param bool $msg Set to true if save is successful
*/

$this->setLayoutTemplate('admin_layout_tpl.php');
echo $this->getJavascriptFile('selectbox.js','groupadmin');

// set up html elements
$this->loadClass('dropdown','htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('link','htmlelements');
$this->loadClass('textinput','htmlelements');
$objConfirm =& $this->newObject('timeoutmessage','htmlelements');
$objHead =& $this->newObject('htmlheading','htmlelements');
$objLayer =& $this->newObject('layer','htmlelements');

// set up language items
$editLabel = $this->objLanguage->languageText('word_edit');
$classLabel = $this->objLanguage->languageText('word_class');
$studentLabel = $this->objLanguage->code2Txt('word_student');
$allHead = $this->objLanguage->code2Txt('mod_pbladmin_allstudentsincourse', 'pbladmin', array('readonlys'=>'students'));
$inClassHead = $this->objLanguage->code2Txt('mod_pbladmin_studentsinclass', 'pbladmin', array('readonlys'=>'students'));
$backLabel = $this->objLanguage->languageText('word_back');
$saveLabel = $this->objLanguage->languageText('word_save');
$saveEditLabel = $this->objLanguage->languageText('mod_pbladmin_saveandeditclass', 'pbladmin');
$emptyLabel = $this->objLanguage->languageText('word_empty');
$confirmLabel = $this->objLanguage->languageText('mod_pbladmin_savesuccessful', 'pbladmin');
$noConfirmLabel = $this->objLanguage->languageText('mod_pbladmin_savefailed', 'pbladmin');
$heading = $this->objLanguage->code2Txt('mod_pbladmin_editstudents', 'pbladmin');

$this->setVarByRef('heading', $heading);

$objTable = new htmltable();
$objTable->width = '99%';
$objTable->cellpadding = '5';

// Add confirm message if set
if(isset($msg)){
    if($msg == 'true'){
        $objConfirm->setMessage($confirmLabel.': '.date('H:i:s'));
    }else{
        $objConfirm->setMessage($noConfirmLabel.': '.date('H:i:s'));
    }
    $objTable->addRow(array($objConfirm->show()));
}
// Add list headings
$objHead->str = $allHead;
$objHead->type = 5;
$left = $objHead->show();

$objHead->str = $inClassHead;
$objHead->type = 5;
$right = $objHead->show();

$objTable->startRow();
$objTable->addCell($left, '','','center');
$objTable->addCell('');
$objTable->addCell($right, '','','center');
$objTable->endRow();

// Students in course
$objDrop = new dropdown('list1[]');
$objDrop->extra = ' style="width:100pt" MULTIPLE SIZE=10 onDblClick="moveSelectedOptions(this.form[\'list1[]\'],this.form[\'list2[]\'],true)"';
if(!empty($users)){
    foreach($users as $line){
        $objDrop->addOption($line['id'], $line['name']);
    }
}
$course = $objDrop->show();
        
// Students in class
$objDrop = new dropdown('list2[]');
$objDrop->extra = ' style="width:100pt" MULTIPLE SIZE=10 onDblClick="moveSelectedOptions(this.form[\'list2[]\'],this.form[\'list1[]\'],true)"';
if(!empty($students)){
    foreach($students as $line){
        $objDrop->addOption($line['id'], $line['name']);
    }
}
$class = $objDrop->show();

// Add / Remove links
$addLinks = '';
$objLink = new link("javascript: moveSelectedOptions( document.frmManage['list1[]'], document.frmManage['list2[]'], true);");
$objLink->link = htmlspecialchars('>>');
$addLinks .= $objLink->show();

$objLink = new link("javascript: moveAllOptions( document.frmManage['list1[]'], document.frmManage['list2[]'], true);");
$objLink->link = htmlspecialchars('All >>');
$addLinks .= '<br>'.$objLink->show();

$objLink = new link("javascript: moveSelectedOptions( document.frmManage['list2[]'], document.frmManage['list1[]'], true);");
$objLink->link = htmlspecialchars('<<');
$addLinks .= '<br>'.$objLink->show();

$objLink = new link("javascript: moveAllOptions( document.frmManage['list2[]'], document.frmManage['list1[]'], true);");
$objLink->link = htmlspecialchars('All <<');
$addLinks .= '<br>'.$objLink->show();

$objTable->startRow();
$objTable->addCell($course, '45%','','center');
$objTable->addCell($addLinks, '10%','','center');
$objTable->addCell($class, '45%','','center');
$objTable->endRow();

// Submit Buttons
$objLink = new link("javascript:selectAllOptions( document.frmManage['list2[]'] ); document.frmManage['button'].value='save'; document.frmManage.submit(); ");
$objLink->link  = $saveLabel;
$links = $objLink->show();

$objLink = new link("javascript:document.frmManage['button'].value='exit'; document.frmManage.submit();");
$objLink->link  = $backLabel;
$links .= '&nbsp;|&nbsp;'.$objLink->show();

$objTable->startRow();
$objTable->addCell($links,'','','center','','colspan=3');
$objTable->endRow();

// Hidden Elements
$objInput = new textinput('button','', 'hidden');
$hidden = $objInput->show();

$objInput = new textinput('id',$id, 'hidden');
$hidden .= $objInput->show();

$objForm = new form('frmManage', $this->uri(array('action'=>'savestudents')));
$objForm->addToForm($hidden);
$objForm->addToForm($objTable->show());

$objLayer->str = $objForm->show();
$objLayer->cssClass = 'odd';
$objLayer->align = 'center';

echo $objLayer->show();
?>