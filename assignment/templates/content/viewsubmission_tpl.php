<?php
//Stylesheet for the slider
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
$str='<script language="JavaScript" src="'.$this->getResourceUri('dhtmlslider.js').'" type="text/javascript"></script>';
//append to the top of the page
$this->appendArrayVar('headerParams', $str);

//setting up of htmlelements helpers
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadclass('textinput','htmlelements');
$objWashout = $this->getObject('washout', 'utilities');
$header = new htmlHeading();
$header->str = $assignment['name'];
$header->type = 1;

$objDateTime = $this->getObject('dateandtime', 'utilities');
$objTrimStr = $this->getObject('trimstr', 'strings');

echo $header->show();
//Setup Tables
$table = $this->newObject('htmltable', 'htmlelements');

$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('word_description', 'system', 'Description').'</strong>', 130);
$table->addCell($objWashout->parseText($assignment['description']), NULL, NULL, NULL, NULL, ' colspan="3"');
$table->endRow();

$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->code2Txt('mod_assignment_lecturer', 'assignment', NULL, '[-author-]').':</strong>', 130);
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

$header = new htmlHeading();

$str = $this->objLanguage->code2Txt('mod_assignment_viewassgnby', 'assignment', NULL, 'View Assignment Submitted by [-person-] at [-time-]');
$str = str_replace('[-person-]', $this->objUser->fullName($submission['userid']), $str);
$str = str_replace('[-time-]', $objDateTime->formatDate($submission['datesubmitted']), $str);

$header->str = $str;
$header->type = 1;

$objIcon = $this->getObject('geticon', 'htmlelements');
$objFileIcon = $this->getObject('fileicons', 'files');

echo '<br />'.$header->show();

$objMark = $this->getObject('markimage', 'utilities');


if ($assignment['format'] == 1) {
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
        $header->type = 1;
        
        echo '<hr />'.$header->show();
        
        $table = $this->newObject('htmltable', 'htmlelements');
        $objMark->value = $submission['mark'];
        
        $table->startRow();
        $table->addCell($objMark->show(), 120);
        
        $content = '<p><strong>'.$this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark').': '.$submission['mark'].'/'.$assignment['mark'].'</strong></p>';
        
        $content .= '<p>'.nl2br($submission['commentinfo']).'</p>';
        
        $table->addCell($content);
        $table->endRow();
        
        echo $table->show();
    }
    
    if ($this->isValid('saveuploadmark')) {
        $header = new htmlHeading();
        $header->str = $this->objLanguage->languageText('mod_assignment_markassgn', 'assignment', 'Mark Assignment');
        $header->type = 1;
        
        echo '<br /><br />'.$header->show();
        
        $form = new form ('upload', $this->uri(array('action'=>'saveuploadmark')));
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
	if($submission['mark'] != NULL){
	 $objTextinput = new textinput('mark',$submission['mark']);
	}else{
	 $objTextinput = new textinput('mark','0');
	}
	$objTextinput->size='5';
//	$objTextinput->value=$submission['mark'];
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
//        $table->addCell("&nbsp;");
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
    
} else {
    echo '<div style="border: 1px solid #000; padding: 10px;">'.$submission['online'].'</div>';
    
    if ($submission['mark'] != NULL  && ($assignment['closing_date'] < date('Y-m-d H:i') || $this->isValid('edit'))) {
        
        $header = new htmlHeading();
        $header->str = $this->objLanguage->languageText('mod_worksheet_result', 'worksheet', 'Result');
        $header->type = 1;
        
        echo '<hr />'.$header->show();
        
        $table = $this->newObject('htmltable', 'htmlelements');
        $objMark->value = $submission['mark'];
        
        $table->startRow();
        $table->addCell($objMark->show(), 120);
        
        $content = '<p><strong>'.$this->objLanguage->languageText('mod_assignment_mark', 'assignment', 'Mark').': '.$submission['mark'].'/'.$assignment['mark'].'</strong></p>';
        
        $content .= '<p>'.nl2br($submission['commentinfo']).'</p>';
        
        $table->addCell($content);
        $table->endRow();
        
        echo $table->show();
    }
    
    if ($this->isValid('saveonlinemark')) {
        $header = new htmlHeading();
        $header->str = $this->objLanguage->languageText('mod_assignment_markassgn', 'assignment', 'Mark Assignment');
        $header->type = 1;
        
        echo '<br /><br />'.$header->show();
        
//        $form = new form ('submitmark', $this->uri(array('action'=>'saveonlinemark')));
        $form = new form ('upload', $this->uri(array('action'=>'saveonlinemark')));
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
*/
	//Setup Tables
	$table = $this->newObject('htmltable', 'htmlelements');
	$objSubTable = new htmltable();
	$objSubTable->width="60%";

	//Insert mark
	$objTextinput = new textinput('mark',$submission['mark']);
	$objTextinput->size='5';
//	$objTextinput->value=$submission['mark'];
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
//        $table->addCell("&nbsp;");
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
//last portion of the slider's script
//function form_widget_amount_slider(targetElId,formTarget,width,min,max,onchangeAction)
$slider=0;
$slider="
<script type=\"text/javascript\">
form_widget_amount_slider('slider_target',document.upload.mark,200,0,".$assignment['mark'].",\"\");
positionSliderImage(true,".$submission['mark'].");
</script>
";
echo $slider;

$link = new link($this->uri(array('action'=>'view', 'id'=>$assignment['id'])));
$link->link = $this->objLanguage->languageText('mod_assignment_returntoassgn', 'assignment', 'Return to Assignment');

echo '<p>'.$link->show().'</p>';

?>
