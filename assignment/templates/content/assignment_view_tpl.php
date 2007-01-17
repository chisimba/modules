<?php
/**
* @package assignment
*/

/**
* Template to view the assignment.
* Students can submit their assignments.
* @param array $data The details of the assignment.
*/

$this->setLayoutTemplate('assignment_layout_tpl.php');

// set up html elements
$objTable =& $this->newObject('htmltable','htmlelements');
$objHead =& $this->newObject('htmlheading','htmlelements');
$objLayer =& $this->newObject('layer','htmlelements');
$objLayer1 =& $this->newObject('layer','htmlelements');
$objForm =& $this->newObject('form','htmlelements');
$objInput =& $this->newObject('textinput','htmlelements');
$objText =& $this->newObject('textarea','htmlelements');
$objButton =& $this->newObject('button','htmlelements');
$objLink =& $this->newObject('link','htmlelements');

// set up language items
$assignmentLabel = $this->objLanguage->languageText('mod_assignment_assignment','assignment');
$heading = $this->objLanguage->languageText('mod_assignment_view','assignment').' '.$assignmentLabel;
$nameLabel = $this->objLanguage->languageText('mod_assignment_wordname','assignment');
$descriptionLabel = $this->objLanguage->languageText('mod_assignment_description','assignment');
$lecturerLabel = ucwords($this->objLanguage->languageText('mod_context_author'));
$resubmitLabel = $this->objLanguage->languageText('mod_assignment_allowresubmit','assignment');
$markLabel = $this->objLanguage->languageText('mod_assignment_mark','assignment');
$dateLabel = $this->objLanguage->languageText('mod_assignment_closingdate','assignment','assignment');
$noLabel = $this->objLanguage->languageText('word_no');
$yesLabel = $this->objLanguage->languageText('word_yes');
$submitLabel = $this->objLanguage->languageText('word_submit');
$completeLabel = $this->objLanguage->languageText('mod_assignment_completeonline','assignment');
$uploadLabel = $this->objLanguage->languageText('mod_assignment_upload','assignment').' '.$assignmentLabel;
$exitLabel = $this->objLanguage->languageText('word_exit');
$percentLabel = $this->objLanguage->languageText('mod_assignment_percentyrmark','assignment');
$objSelectFile = $this->newObject('selectfile', 'filemanager');

$lnPlain = $this->objLanguage->languageText('mod_testadmin_plaintexteditor');
$lnWysiwyg = $this->objLanguage->languageText('mod_testadmin_wysiwygeditor');

$this->setVarByRef('heading', $heading);

// submission of the assignment
$javascript="<script language=\"JavaScript\">
    function submitForm(val){
    document.upload.action.value=val;
    document.upload.submit();
    }
    </script>";
echo $javascript;

$str = '';
$objTable->cellpadding=5;
$objTable->width='99%';

if(!empty($data)){
    // Display the assignment
    $objTable->startRow();
    $objTable->addCell('<b>'.$nameLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$data[0]['name'],'45%');
    $objTable->addCell('<b>'.$lecturerLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$this->objUser->fullname($data[0]['userid']),'55%');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell('<b>'.$markLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$data[0]['mark']);
    $objTable->addCell('<b>'.$percentLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$data[0]['percentage'].' %');
    $objTable->endRow();

    if($data[0]['resubmit']){
        $resubmit = $yesLabel;
    }else{
        $resubmit = $noLabel;
    }
    $objTable->startRow();
    $objTable->addCell('<b>'.$resubmitLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$resubmit);
    $objTable->addCell('<b>'.$dateLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$this->formatDate($data[0]['closing_date']));
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell('<b>'.$descriptionLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell($data[0]['description'],'','','','','colspan="2"');
    $objTable->endRow();

    $objLayer->cssClass = 'odd';
    $objLayer->str = $objTable->show();

    $str = $objLayer->show();

    // Section for submitting the assignment by upload or online if user is a student
    $objHead->type = 4;
    $objHead->str = $submitLabel.' '.$assignmentLabel;
    $str .= $objHead->show();

    // Determine format of submission: upload or online
    if($data[0]['format']){
        // upload a file, add hidden fileId if multiple submissions
        $inputStr = '<b>'.$uploadLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

	$objSelectFile->name = 'file';

	//$form->addToForm($objSelectFile->show());
        //$objInput = new textinput('file');
        //$objInput->fldType = 'file';
        //$inputStr .= $objSelectFile->show();

        if(!empty($data[0]['fileid'])){
            $objInput = new textinput('fileid', $data[0]['fileid']);
            $objInput->fldType = 'hidden';
            $inputStr .= $objInput->show();
        }
    }else{
        $dOnline = '';
        // Complete assignment online, display assignment if multiple submissions
        if(!empty($data[0]['online'])){
            $dOnline = $data[0]['online'];
        }

        $inputStr = '<b>'.$completeLabel.':</b>';

        $type = $this->getParam('editor', 'ww');
        if($type == 'plaintext'){
            // Hidden element for the editor type
            $objInput = new textinput('editor', 'ww', 'hidden');

            $objText = new textarea('text', $dOnline, 15, 80);
            $inputStr .= $objText->show();

            $objLink = new link("javascript:submitform('changeeditor')");
            $objLink->link = $lnWysiwyg;
            $inputStr .= '<br/>'.$objLink->show().$objInput->show();
        }else{
            // Hidden element for the editor type
            $objInput = new textinput('editor', 'plaintext', 'hidden');

            $objEditor = $this->newObject('htmlarea', 'htmlelements');
            $objEditor->init('text', $dOnline, '500px', '500px');
            $objEditor->setDefaultToolBarSetWithoutSave();

            $inputStr .= $objEditor->show();

            $objLink = new link("javascript:submitForm('changeeditor')");
            $objLink->link = $lnPlain;
            $inputStr .= '<br />'.$objLink->show().$objInput->show();
        }

    }
    // hidden elements
    $objInput = new textinput('format', $data[0]['format'], 'hidden');
    $hidden = $objInput->show();

    $objInput = new textinput('id', $data[0]['id'], 'hidden');
    $hidden .= $objInput->show();

    $objInput = new textinput('action', 'submit', 'hidden');
    $hidden .= $objInput->show();

    if(!empty($data[0]['submitid'])){
        $objInput = new textinput('submitid', $data[0]['submitid']);
        $objInput->fldType = 'hidden';
        $hidden .= $objInput->show();
    }

    // submit buttons and form
   
    $objButton = new button('save', $submitLabel);
    $objButton->setToSubmit();
    $btns = $objButton->show().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

    $objButton = new button('save', $exitLabel);
    $objButton->setToSubmit();
    $btns .= $objButton->show();
	
    $objForm = new form('upload', $this->uri(''));
	$objForm->addToForm($objSelectFile->show());
    $objForm->extra = " ENCTYPE='multipart/form-data'";

    $objForm->addToForm($inputStr);
 
    $objForm->addToForm($hidden);
    $objForm->addToForm('<br />'.$btns);

    $layerStr = $objForm->show();

    $objLayer1->str = $layerStr;
    $objLayer1->align = 'center';
    $str .= $objLayer1->show();
}

echo $str;
?>
