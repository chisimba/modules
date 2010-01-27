<?php
//echo '<pre>';
//var_dump($submission);
//echo '</pre>';
// StyleSheet for the slider
$css="
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
$this->appendArrayVar('headerParams', $css);
// JavaScript for the slider
$js='<script language="JavaScript" src="'.$this->getResourceUri('dhtmlslider.js').'" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $js);
// Load classes
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadclass('textinput','htmlelements');
$objDateTime = $this->getObject('dateandtime', 'utilities');
$objTrimStr = $this->getObject('trimstr', 'strings');
$objWashout = $this->getObject('washout', 'utilities');
if (!is_null($submission['mark'])) {
    $submission['mark'] = (int)$submission['mark'];
}
// Section 1
// Heading
$header = new htmlHeading();
$header->str = $assignment['name'];
$header->type = 1;
echo $header->show();
// Table
$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('word_description', 'system', 'Description').'</strong>', 130);
$table->addCell($objWashout->parseText($assignment['description']), NULL, NULL, NULL, NULL, ' colspan="3"');
$table->endRow();
$table->startRow();
$table->addCell('<strong>'.ucwords($this->objLanguage->code2Txt('mod_assignment_lecturer', 'assignment', NULL, '[-author-]')).':</strong>', 130);
$table->addCell($this->objUser->fullName($assignment['userid']));
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_worksheet_totalmark', 'worksheet', 'Total Mark').'</strong>', 130);
$table->addCell($assignment['mark']);
$table->endRow();
$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_assignment_openingdate', 'assignment', 'Opening Date').'</strong>', 130);
$table->addCell($objDateTime->formatDate($assignment['opening_date']));
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_assignment_percentyrmark', 'assignment', 'Percentage of year mark').':</strong>', 200, NULL, NULL, 'nowrap');
$table->addCell($assignment['percentage'].'%');
$table->endRow();
$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_assignment_closingdate', 'assignment', 'Closing Date').'</strong>', 130);
$table->addCell($objDateTime->formatDate($assignment['closing_date']));
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_assignment_assignmenttype', 'assignment', 'Assignment Type').'</strong>', 130);
if ($assignment['format'] == '0') {
    $table->addCell($this->objLanguage->languageText('mod_assignment_online', 'assignment', 'Online'));
} else {
    $table->addCell($this->objLanguage->languageText('mod_assignment_upload', 'assignment', 'Upload'));
}
$table->endRow();
echo $table->show();
// Section 2
// Heading
$header = new htmlHeading();
$str = $this->objLanguage->code2Txt('mod_assignment_viewassgnby', 'assignment', NULL); //'View ssignment Submitted by [-person-] at [-time-]'
$str = str_replace('[-person-]', $this->objUser->fullName($submission['userid']), $str);
$str = str_replace('[-time-]', $objDateTime->formatDate($submission['datesubmitted']), $str);
$header->str = $str;
$header->type = 3;
echo $header->show();
//
$objIcon = $this->getObject('geticon', 'htmlelements');
$objFileIcon = $this->getObject('fileicons', 'files');
$objMark = $this->getObject('markimage', 'utilities');
if ($assignment['format'] == '1') {
    // Upload
    $objFile = $this->getObject('dbfile', 'filemanager');
    $fileName = $objFile->getFileName($submission['studentfileid']);
    $downloadLink = new link ($this->uri(array('action'=>'downloadfile', 'id'=>$submission['id'])));
    $downloadLink->link = $this->objLanguage->languageText('word_download', 'system', 'Download');
    echo '<p>'.$objFileIcon->getFileIcon($fileName).' '.$downloadLink->show().'</p>';
    $filePath = $this->objAssignmentSubmit->getAssignmentFilename($submission['id'], $submission['studentfileid']);
    // PHP File that is assignment
    $destination1 = dirname($filePath).'/'.$submission['id'].'.php';
    // HTML file - needed for conversion
    $destination2 = dirname($filePath).'/'.$submission['id'].'.html';
    // CHeck if file exists, else need to convert
    if (!file_exists($destination1)) {
        if (!file_exists($destination2)) {
            // Convert Document
            $objConvert = $this->getObject('convertdoc', 'documentconverter');
            $objConvert->convert($filePath, $destination2);
        }
        // If successfully converted, rename to .php
        if (file_exists($destination2)) {
            copy($destination2, $destination1);
            unlink($destination2);
            $contents =  file_get_contents($destination1);
            $contents = '<?php if (isset($permission) && $permission) { ?>'.$contents.'<?php } ?>';
            //var_dump(chmod($destination1, 0777));
            file_put_contents($destination1, $contents);
        }
    }
    //var_dump($filePath);
    //var_dump($destination);
    if (file_exists($destination1)) {
        $this->loadClass('iframe', 'htmlelements');
        $header = new htmlHeading();
        $header->str = $this->objLanguage->languageText('word_preview', 'system', 'Preview');
        $header->type = 1;
        echo '<br />'.$header->show();
        $iframe = new iframe();
        $iframe->width = '100%';
        $iframe->height = 400;
        $iframe->src = $this->uri(array('action'=>'viewhtmlsubmission', 'id'=>$submission['id']));
        echo $iframe->show();
    }
    if ($submission['mark'] != NULL && ($assignment['closing_date'] < date('Y-m-d H:i') || $this->isValid('edit'))) {
        $header = new htmlHeading();
        $header->str = $this->objLanguage->languageText('mod_worksheet_result', 'worksheet', 'Result');
        $header->type = 3;
        echo $header->show();
        $table = $this->newObject('htmltable', 'htmlelements');
        $objMark->value = (float)$submission['mark'];
        $table->startRow();
        $table->addCell($objMark->show(), 120);
        $content = '<p><strong>'.$this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark').': '.$submission['mark'].'/'.$assignment['mark'].'</strong></p>';
        $content .= '<p>'.nl2br($submission['commentinfo']).'</p>';
        $table->addCell($content);
        $table->endRow();
        echo $table->show();
    }
    if ($this->isValid('saveuploadmark')) {
        // Header
        $header = new htmlHeading();
        $header->str = $this->objLanguage->languageText('mod_assignment_markassgn', 'assignment', 'Mark Assignment');
        $header->type = 3;
        echo $header->show();
        // Form
        $form = new form ('_form', $this->uri(array('action'=>'saveuploadmark')));
        $form->extra = 'enctype="multipart/form-data"';
        $hiddenInput = new hiddeninput('id', $submission['id']);
        $textArea = new textarea('commentinfo');
        $textArea->value = $submission['commentinfo'];
        $button = new button('savemark', $this->objLanguage->languageText('mod_assignment_markassgn', 'assignment', 'Mark Assignment'));
        $button->setToSubmit();
/*
        $slider = $this->newObject('slider', 'htmlelements');
        $slider->value = $submission['mark'];
        $slider->maxValue = $assignment['mark'];

        $table = $this->newObject('htmltable', 'htmlelements');
*/
    	//Setup Tables
    	$table = $this->newObject('htmltable', 'htmlelements');
    	$objSubTable = new htmltable();
    	$objSubTable->width="60%";
    	//Insert mark
        $objTextinput = new textinput('mark',is_null($submission['mark'])?'0':$submission['mark']);
    	$objTextinput->size='5';
    	$objTextinput->extra=' maxlength=\'4\'';
    	$objSubTable->startRow();
    	$objSubTable->addCell($objTextinput->show().' / '.$assignment['mark']." ".$this->objLanguage->languageText('mod_assignment_typeorslider', 'assignment', 'Mark'),'70%','','left');
    	$objSubTable->addCell("&nbsp;");
    	$objSubTable->endRow();
     	$objSubTable->startRow();
    	$objSubTable->addCell("&nbsp;",'70%','','left','',' id=\'slider_target\'');
    	$objSubTable->addCell("&nbsp;");
    	$objSubTable->endRow();
        $table->startRow();
        //$table->addCell("&nbsp;");
        $table->addCell($this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark'), 120);
        $table->addCell($objSubTable->show());
        $table->endRow();
    	//Spacer
        $table->startRow();
        $table->addCell("&nbsp;");
        $table->addCell("&nbsp;");
        $table->endRow();
    /*
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark'), 120);
        $table->addCell($slider->show());
        $table->endRow();
    */
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_assignment_comment', 'assignment', 'Comment'));
        $table->addCell($textArea->show());
        $table->endRow();
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_assignment_reviewedassgn', 'assignment', 'Reviewed Assignment'));
        $table->addCell('<input type="file" name="lectfile">');
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;');
        $table->addCell($button->show());
        $table->endRow();
        $form->addToForm($hiddenInput->show().$table->show());
        echo $form->show();
    }
}
else {
    // Online
    echo '<div style="border: 1px solid #000; padding: 10px;">'.$submission['online'].'</div>';
    if ($submission['mark'] != NULL  && ($assignment['closing_date'] < date('Y-m-d H:i') || $this->isValid('edit'))) {
        // Header
        $header = new htmlHeading();
        $header->str = $this->objLanguage->languageText('mod_worksheet_result', 'worksheet', 'Result');
        $header->type = 3;
        echo $header->show();
        // Table
        $table = $this->newObject('htmltable', 'htmlelements');
        $objMark->value = (float)$submission['mark'];
        $table->startRow();
        $table->addCell($objMark->show(), 120);
        $content = '<p><strong>'.$this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark').': '.$submission['mark'].'/'.$assignment['mark'].'</strong></p>';
        $content .= '<p>'.nl2br($submission['commentinfo']).'</p>';
        $table->addCell($content);
        $table->endRow();
        echo $table->show();
    }
    if ($this->isValid('saveonlinemark')) {
        // Header
        $header = new htmlHeading();
        $header->str = $this->objLanguage->languageText('mod_assignment_markassgn', 'assignment', 'Mark Assignment');
        $header->type = 3;
        echo $header->show();
        // Form
        $form = new form ('_form', $this->uri(array('action'=>'saveonlinemark')));
        //$form->extra = 'enctype="multipart/form-data"';
        $hiddenInput = new hiddeninput('id', $submission['id']);
        $textArea = new textarea('commentinfo');
        $textArea->value = $submission['commentinfo'];
        $button = new button('savemark', $this->objLanguage->languageText('mod_assignment_markassgn', 'assignment', 'Mark Assignment'));
        $button->setToSubmit();
/*
        $slider = $this->newObject('slider', 'htmlelements');
        $slider->value = $submission['mark'];
        $slider->maxValue = $assignment['mark'];
*/
    	// Table
    	$table = $this->newObject('htmltable', 'htmlelements');
    	$objSubTable = new htmltable();
    	$objSubTable->width="60%";
    	//Insert mark
    	$objTextinput = new textinput('mark',is_null($submission['mark'])?'0':$submission['mark']);
    	$objTextinput->size='5';
    	$objTextinput->extra=' maxlength=\'4\'';
    	$objSubTable->startRow();
    	$objSubTable->addCell($objTextinput->show().' / '.$assignment['mark']." ".$this->objLanguage->languageText('mod_assignment_typeorslider', 'assignment', 'Mark'),'70%','','left');
    	$objSubTable->addCell("&nbsp;");
    	$objSubTable->endRow();
     	$objSubTable->startRow();
    	$objSubTable->addCell("&nbsp;",'70%','','left','',' id=\'slider_target\'');
    	$objSubTable->addCell("&nbsp;");
    	$objSubTable->endRow();
        $table->startRow();
        //$table->addCell("&nbsp;");
        $table->addCell($this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark'), 120);
        $table->addCell($objSubTable->show());
        $table->endRow();
    	//Spacer
        $table->startRow();
        $table->addCell("&nbsp;");
        $table->addCell("&nbsp;");
        $table->endRow();
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_assignment_comment', 'assignment', 'Comment'));
        $table->addCell($textArea->show());
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;');
        $table->addCell($button->show());
        $table->endRow();
        $form->addToForm($hiddenInput->show().$table->show());
        echo $form->show();
    }
}
$link = new link($this->uri(array('action'=>'view', 'id'=>$assignment['id'])));
$link->link = $this->objLanguage->languageText('mod_assignment_returntoassgn', 'assignment', 'Return to Assignment');
echo '<p>'.$link->show().'</p>';
//last portion of the slider's script
//function form_widget_amount_slider(targetElId,formTarget,width,min,max,onchangeAction)
$sliderJS="
    <script type=\"text/javascript\">
    set_form_widget_amount_slider_handle('".$this->getResourceUri('slider_handle.gif')."');
    form_widget_amount_slider('slider_target',document._form.mark,200,0,".$assignment['mark'].",false);
    </script>
";
echo $sliderJS;
?>